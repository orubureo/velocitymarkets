<?php

namespace App\Services;

use App\Models\Market;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PriceService
{
    public function currentPrice(string $symbol): float
    {
        $market = Market::where('symbol', $symbol)->where('is_active', true)->firstOrFail();

        return Cache::remember("price:{$symbol}", 10, function () use ($market, $symbol) {
            try {
                $response = Http::timeout(5)->get('https://api.coingecko.com/api/v3/simple/price', [
                    'ids' => $market->coingecko_id,
                    'vs_currencies' => 'usd',
                ]);

                $price = (float) $response->json("{$market->coingecko_id}.usd");

                if ($price > 0) {
                    Cache::put("price:{$symbol}:last_known", $price, now()->addDay());
                }

                return $price;
            } catch (ConnectionException $e) {
                return (float) Cache::get("price:{$symbol}:last_known", 0);
            }
        });
    }

    public function supportedMarkets()
    {
        return Market::where('is_active', true)->orderBy('sort_order')->get();
    }

    /**
     * Bulk-fetch official coin logos for every supported market in a single request,
     * keyed by coingecko_id.
     */
    public function marketIcons(): array
    {
        return collect($this->marketSnapshots())->pluck('image', 'id')->filter()->all();
    }

    /**
     * Live ticker data (price + 24h change + icon) for a curated set of markets,
     * keyed by symbol so blade can look each one up directly. Pass no symbols to
     * get every supported market.
     */
    public function tickerMarkets(array $symbols = []): array
    {
        $snapshots = collect($this->marketSnapshots())->keyBy('id');

        $markets = $symbols
            ? $this->supportedMarkets()->whereIn('symbol', $symbols)->sortBy(fn ($m) => array_search($m->symbol, $symbols))
            : $this->supportedMarkets();

        return $markets
            ->map(function ($market) use ($snapshots) {
                $snapshot = $snapshots->get($market->coingecko_id);

                if (! $snapshot || $snapshot['price'] === null) {
                    return null;
                }

                return [
                    'symbol' => $market->symbol,
                    'display_name' => $market->display_name,
                    'price' => $snapshot['price'],
                    'change_pct' => $snapshot['change_pct'],
                    'image' => $snapshot['image'],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Bulk snapshot (image, price, 24h change) for every supported market in a
     * single request, keyed by coingecko_id. Cached briefly so price/change stay
     * reasonably live without hammering the API on every request.
     */
    protected function marketSnapshots(): array
    {
        return Cache::remember('market-snapshots', now()->addSeconds(60), function () {
            $ids = $this->supportedMarkets()->pluck('coingecko_id')->filter()->unique()->values();

            if ($ids->isEmpty()) {
                return [];
            }

            try {
                $response = Http::timeout(10)->get('https://api.coingecko.com/api/v3/coins/markets', [
                    'vs_currency' => 'usd',
                    'ids' => $ids->implode(','),
                    'per_page' => 250,
                ]);

                return collect($response->json())
                    ->filter(fn ($coin) => is_array($coin) && isset($coin['id']))
                    ->map(fn ($coin) => [
                        'id' => $coin['id'],
                        'image' => $coin['image'] ?? null,
                        'price' => $coin['current_price'] ?? null,
                        'change_pct' => $coin['price_change_percentage_24h'] ?? null,
                    ])
                    ->all();
            } catch (ConnectionException $e) {
                return [];
            }
        });
    }
}
