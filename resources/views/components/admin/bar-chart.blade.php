@props([
    'uid',
    'labels',
    'shortLabels' => null,
    'series',
    'trend' => null,
    'mode' => 'baseline',
    'format' => 'int',
    'height' => 'h-40 sm:h-44',
    'labelEvery' => 5,
    'labelEverySm' => 10,
    'emptyIcon' => 'chart-bar',
    'emptyMessage' => 'No data yet.',
    'tableCaption' => 'Chart data',
    'summary' => null,
])

@php
    $shortLabels = $shortLabels ?? $labels;
    $n = count($labels);
    $vbW = max($n, 1) * 10;
    $diverging = $mode === 'diverging';
    $base = $diverging ? 50 : 100;
    $span = $diverging ? 50 : 100;

    $allValues = collect($series)->flatMap(fn ($s) => $s['values'])->all();
    $peak = $allValues ? (float) max($allValues) : 0.0;
    $isEmpty = $peak <= 0.0;

    if ($format === 'currency') {
        $ceiling = \App\Support\DailySeries::niceCeiling($peak, 100.0, [1, 2, 2.5, 5, 10]);
        $fmt = fn ($v) => '$'.number_format($v, 0);
    } else {
        $ceiling = \App\Support\DailySeries::niceCeiling($peak, 4.0, [1, 2, 4, 5, 10]);
        $fmt = fn ($v) => number_format($v);
    }

    // Per-series bar geometry: [key, direction, fillClass, gradientId, bars => [x,y,w,h,value]]
    $seriesCount = count($series);

    $plotted = collect($series)->map(function ($s) use ($n, $ceiling, $base, $span, $uid, $seriesCount) {
        $bars = [];

        for ($i = 0; $i < $n; $i++) {
            $v = (float) ($s['values'][$i] ?? 0);
            $h = $v > 0 ? max($v / $ceiling * $span, $span >= 100 ? 1.5 : 1.2) : 0.0;
            $y = $s['direction'] === 'down' ? $base : $base - $h;

            $bars[] = ['x' => $i * 10 + 2, 'y' => $y, 'w' => 6, 'h' => $h, 'value' => $v];
        }

        return [
            'key' => $s['key'],
            'direction' => $s['direction'],
            'fillClass' => $s['fillClass'] ?? 'text-accent',
            'gradientId' => $seriesCount > 1 ? "bar-{$uid}-{$s['key']}" : "bar-{$uid}",
            'bars' => $bars,
        ];
    });

    // Trend polyline, plotted against the same ceiling so it can never visually exceed the axis.
    $trendPoints = null;

    if ($trend) {
        $points = [];

        for ($i = 0; $i < $n; $i++) {
            $tv = (float) ($trend[$i] ?? 0);
            $ty = $base - ($tv / $ceiling * $span);
            $ty = max($base - $span, min($base + $span, $ty));
            $points[] = ($i * 10 + 5).','.round($ty, 2);
        }

        $trendPoints = implode(' ', $points);
    }
@endphp

<figure role="group" class="w-full {{ $isEmpty ? '' : 'relative' }}">
    <figcaption class="sr-only">{{ $tableCaption }}</figcaption>

    @if ($isEmpty)
        <div class="relative {{ $height }} rounded-xl border border-dashed border-zinc-200 dark:border-zinc-800 flex flex-col items-center justify-center gap-2 text-center px-4">
            <div class="absolute inset-x-4 top-1/4 h-px bg-zinc-100 dark:bg-zinc-800/60"></div>
            <div class="absolute inset-x-4 top-1/2 h-px bg-zinc-100 dark:bg-zinc-800/60"></div>
            <div class="absolute inset-x-4 top-3/4 h-px bg-zinc-100 dark:bg-zinc-800/60"></div>
            <flux:icon name="{{ $emptyIcon }}" class="relative size-6 text-zinc-300 dark:text-zinc-700" />
            <flux:text size="sm" class="relative text-zinc-500">{{ $emptyMessage }}</flux:text>
        </div>
    @else
        <div x-data="{ hover: null }" class="relative {{ $height }} min-w-0">
            <svg viewBox="0 0 {{ $vbW }} 100" preserveAspectRatio="none" class="w-full h-full block {{ $plotted->count() === 1 ? $plotted[0]['fillClass'] : '' }}" aria-hidden="true" focusable="false">
                <defs>
                    @foreach ($plotted as $s)
                        <linearGradient id="{{ $s['gradientId'] }}" x1="0" y1="0%" x2="0" y2="100%" class="{{ $plotted->count() > 1 ? $s['fillClass'] : '' }}">
                            @if ($s['direction'] === 'down')
                                <stop offset="0%" stop-color="currentColor" stop-opacity="1" />
                                <stop offset="100%" stop-color="currentColor" stop-opacity="0.45" />
                            @else
                                <stop offset="0%" stop-color="currentColor" stop-opacity="0.45" />
                                <stop offset="100%" stop-color="currentColor" stop-opacity="1" />
                            @endif
                        </linearGradient>
                    @endforeach
                </defs>

                {{-- Dashed edge gridlines --}}
                <line x1="0" y1="0" x2="{{ $vbW }}" y2="0" vector-effect="non-scaling-stroke" stroke-dasharray="2 2" class="stroke-zinc-200 dark:stroke-zinc-800" />
                @if ($diverging)
                    <line x1="0" y1="100" x2="{{ $vbW }}" y2="100" vector-effect="non-scaling-stroke" stroke-dasharray="2 2" class="stroke-zinc-200 dark:stroke-zinc-800" />
                @else
                    <line x1="0" y1="50" x2="{{ $vbW }}" y2="50" vector-effect="non-scaling-stroke" stroke-dasharray="2 2" class="stroke-zinc-200 dark:stroke-zinc-800" />
                @endif

                {{-- Bars --}}
                @foreach ($plotted as $s)
                    <g>
                        @foreach ($s['bars'] as $bar)
                            @if ($bar['h'] > 0)
                                <rect x="{{ $bar['x'] }}" y="{{ $bar['y'] }}" width="{{ $bar['w'] }}" height="{{ $bar['h'] }}" rx="1" fill="url(#{{ $s['gradientId'] }})" />
                            @endif
                        @endforeach
                    </g>
                @endforeach

                {{-- Solid baseline/center line — painted after the bars so it stays legible cutting across them --}}
                <line x1="0" y1="{{ $base }}" x2="{{ $vbW }}" y2="{{ $base }}" vector-effect="non-scaling-stroke" class="stroke-zinc-300 dark:stroke-zinc-700" />

                @if ($trendPoints)
                    <polyline points="{{ $trendPoints }}" fill="none" vector-effect="non-scaling-stroke" stroke-dasharray="4 3" stroke-width="1.5" class="stroke-zinc-500 dark:stroke-zinc-400" />
                @endif

                {{-- Transparent hit targets, one per day, painted last so they sit above everything --}}
                @for ($i = 0; $i < $n; $i++)
                    <rect x="{{ $i * 10 }}" y="0" width="10" height="100" fill="transparent"
                        x-on:mouseenter="hover = {{ $i }}" x-on:mouseleave="hover = null">
                        <title>{{ $labels[$i] }}@foreach ($series as $s) — {{ $s['label'] ?? ucfirst($s['key']) }}: {{ $fmt((float) ($s['values'][$i] ?? 0)) }}@endforeach</title>
                    </rect>
                @endfor
            </svg>

            {{-- Floating tooltip, kept in HTML so preserveAspectRatio="none" never distorts it --}}
            <template x-if="hover !== null">
                <div class="absolute -top-2 -translate-y-full px-2.5 py-1.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-xs shadow-lg pointer-events-none whitespace-nowrap z-10"
                    x-bind:style="`left: ${Math.min(91, Math.max(9, ((hover + 0.5) / {{ $n }}) * 100))}%; transform: translate(-50%, -100%);`">
                    <div class="font-semibold" x-text="{{ json_encode($labels) }}[hover]"></div>
                    @foreach ($series as $idx => $s)
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block size-1.5 rounded-full {{ $s['dotClass'] ?? 'bg-accent' }}"></span>
                            <span>{{ $s['label'] ?? ucfirst($s['key']) }}: <span x-text="{{ json_encode(array_map($fmt, $s['values'])) }}[hover]"></span></span>
                        </div>
                    @endforeach
                </div>
            </template>

            {{-- X-axis labels — spaced back from the last (most recent) day, not forward
                 from the first, so "today" always gets a label instead of trailing off
                 unlabeled when $n - 1 isn't a multiple of $labelEvery. --}}
            <div class="flex mt-1 text-[10px] text-zinc-400 dark:text-zinc-500">
                @for ($i = 0; $i < $n; $i++)
                    <div class="flex-1 text-center whitespace-nowrap overflow-visible">
                        @if ($labelEvery > 0 && ($n - 1 - $i) % $labelEvery === 0)
                            <span class="hidden md:inline">{{ $labels[$i] }}</span>
                        @endif
                        @if ($labelEverySm > 0 && ($n - 1 - $i) % $labelEverySm === 0)
                            <span class="md:hidden">{{ $shortLabels[$i] }}</span>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    @endif

    @if ($summary)
        <p class="sr-only">{{ $summary }}</p>
    @endif

    {{-- The only data path for assistive tech, since the SVG above is aria-hidden.
         sr-only goes on this wrapping div, not the <table> itself — a table's rows
         can't shrink below their content height, so overflow:hidden on the table
         element doesn't actually clip it and it renders at full height, inflating
         the page's scrollable area despite being visually hidden. --}}
    <div class="sr-only">
        <table>
            <caption>{{ $tableCaption }}</caption>
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    @foreach ($series as $s)
                        <th scope="col">{{ $s['label'] ?? ucfirst($s['key']) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < $n; $i++)
                    <tr>
                        <th scope="row">{{ $labels[$i] }}</th>
                        @foreach ($series as $s)
                            <td>{{ $fmt((float) ($s['values'][$i] ?? 0)) }}</td>
                        @endforeach
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</figure>
