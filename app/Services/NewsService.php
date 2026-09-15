<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class NewsService
{
    /**
     * Latest headlines from Cointelegraph's public RSS feed, freshly parsed
     * and cached briefly. Returns an empty array on any failure so the view
     * can render an honest empty state instead of fake placeholder articles.
     *
     * @return array<int, array{title: string, link: string, excerpt: string, image: ?string, published_at: ?Carbon, source: string}>
     */
    public function latestHeadlines(int $limit = 12): array
    {
        $headlines = Cache::remember('news:cointelegraph', 10, function () {
            try {
                $response = Http::timeout(8)->get('https://cointelegraph.com/rss');

                if (! $response->successful()) {
                    return [];
                }

                return $this->parseFeed($response->body());
            } catch (ConnectionException $e) {
                return [];
            }
        });

        return array_slice($headlines, 0, $limit);
    }

    /**
     * Parse the raw RSS XML body into a flat array of headline data.
     * Defensive against malformed/empty XML — returns [] rather than throwing.
     *
     * @return array<int, array{title: string, link: string, excerpt: string, image: ?string, published_at: ?Carbon, source: string}>
     */
    protected function parseFeed(string $body): array
    {
        if (trim($body) === '') {
            return [];
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($body);
        libxml_clear_errors();

        if ($xml === false || ! isset($xml->channel->item)) {
            return [];
        }

        $headlines = [];

        foreach ($xml->channel->item as $item) {
            $description = (string) $item->description;

            $headlines[] = [
                'title' => trim((string) $item->title),
                'link' => trim((string) $item->link),
                'excerpt' => $this->excerptFrom($description),
                'image' => $this->imageFrom($item, $description),
                'published_at' => $this->parseDate((string) $item->pubDate),
                'source' => 'Cointelegraph',
            ];
        }

        return $headlines;
    }

    /**
     * Strip HTML from the item's <description> and trim to a short excerpt.
     */
    protected function excerptFrom(string $description): string
    {
        $text = trim(html_entity_decode(strip_tags($description), ENT_QUOTES | ENT_HTML5));
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;

        return mb_strlen($text) > 160 ? rtrim(mb_substr($text, 0, 160)).'…' : $text;
    }

    /**
     * A real thumbnail URL for the item, preferring the <enclosure> tag,
     * falling back to <media:content>, then to an <img> embedded in the
     * description HTML. Returns null (never a fabricated URL) if none exist.
     */
    protected function imageFrom(SimpleXMLElement $item, string $description): ?string
    {
        $url = (string) ($item->enclosure['url'] ?? '');

        if ($url === '') {
            $namespaces = $item->getNamespaces(true);

            if (isset($namespaces['media'])) {
                $media = $item->children($namespaces['media']);
                $url = (string) ($media->content['url'] ?? '');
            }
        }

        if ($url === '' && preg_match('/<img[^>]+src="([^"]+)"/i', $description, $matches)) {
            $url = $matches[1];
        }

        return $url !== '' ? $url : null;
    }

    protected function parseDate(string $pubDate): ?Carbon
    {
        if ($pubDate === '') {
            return null;
        }

        try {
            return Carbon::parse($pubDate);
        } catch (\Exception $e) {
            return null;
        }
    }
}
