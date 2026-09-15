<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/**
 * Shared helpers for building fixed-length daily time series for dashboard charts.
 */
class DailySeries
{
    /**
     * Ordered list of Y-m-d date-string keys, oldest first, starting at $start.
     *
     * @return Collection<int, string>
     */
    public static function spine(CarbonImmutable $start, int $days): Collection
    {
        return collect(range(0, $days - 1))
            ->map(fn (int $i) => $start->addDays($i)->toDateString());
    }

    /**
     * Smallest "nice" ceiling >= max($max, $floor), stepping through $steps * 10^n.
     * Clamping to $floor before log10() avoids log10(0) (-INF) when every value is zero.
     */
    public static function niceCeiling(float $max, float $floor, array $steps): float
    {
        $clamped = max($max, $floor);
        $magnitude = 10 ** floor(log10($clamped));

        foreach ($steps as $step) {
            $candidate = $step * $magnitude;

            if ($clamped <= $candidate) {
                return $candidate;
            }
        }

        return end($steps) * $magnitude;
    }

    /**
     * Trailing mean over up to $window prior days, inclusive of the current index.
     *
     * @param  array<int, float>  $values
     * @return array<int, float>
     */
    public static function trailingAverage(array $values, int $window): array
    {
        $result = [];

        foreach (array_values($values) as $i => $value) {
            $start = max(0, $i - $window + 1);
            $slice = array_slice($values, $start, $i - $start + 1);
            $result[] = array_sum($slice) / count($slice);
        }

        return $result;
    }
}
