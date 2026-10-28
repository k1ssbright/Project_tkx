<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @var array<string, array{label: string, days?: int, months?: int}>
     */
    public const array PERIODS = [
        '7d' => ['label' => '7 วัน', 'days' => 7],
        '30d' => ['label' => '30 วัน', 'days' => 30],
        '6m' => ['label' => '6 เดือน', 'months' => 6],
        '12m' => ['label' => '1 ปี', 'months' => 12],
    ];

    /**
     * @var list<string>
     */
    private const array CATEGORY_COLORS = ['#000000', '#fe7b07', '#c4b59d', '#8a847c'];

    private const int CHART_WIDTH = 1000;

    private const int CHART_HEIGHT = 300;

    /**
     * @var list<array{label: string, from: int, to: int}>
     */
    private const array TIME_SLOTS = [
        ['label' => '00-04', 'from' => 0, 'to' => 3],
        ['label' => '04-08', 'from' => 4, 'to' => 7],
        ['label' => '08-12', 'from' => 8, 'to' => 11],
        ['label' => '12-16', 'from' => 12, 'to' => 15],
        ['label' => '16-20', 'from' => 16, 'to' => 19],
        ['label' => '20-24', 'from' => 20, 'to' => 23],
    ];

    public function __invoke(Request $request): View
    {
        $request->validate([
            'period' => ['nullable', Rule::in(array_keys(self::PERIODS))],
        ]);

        $period = $request->string('period', '30d')->toString();
        $now = now();
        $start = $this->periodStart($period, $now);
        $previousStart = $start->copy()->sub($start->diff($now));
        $days = max(1, (int) round($start->diffInDays($now)));

        $clicks = $this->viewsBetween($start, $now)->count();
        $previousClicks = $this->viewsBetween($previousStart, $start)->count();
        $viewedProducts = $this->viewsBetween($start, $now)->distinct()->count('product_id');
        $previousViewedProducts = $this->viewsBetween($previousStart, $start)->distinct()->count('product_id');
        $averagePerDay = round($clicks / $days, 1);
        $previousAveragePerDay = round($previousClicks / $days, 1);

        $topProducts = $this->topProducts($start, $now, $clicks);
        $leader = $topProducts->first();

        return view('admin.dashboard', [
            'periods' => self::PERIODS,
            'period' => $period,
            'start' => $start,
            'hasViews' => ProductView::query()->exists(),
            'sampleCount' => ProductView::query()->where('is_sample', true)->count(),
            'kpis' => [
                ['label' => 'คลิกเข้าชมทั้งหมด', 'value' => number_format($clicks), 'raw' => $clicks, 'decimals' => 0, 'delta' => $this->delta($clicks, $previousClicks), 'note' => null, 'icon' => 'clicks'],
                ['label' => 'สินค้าที่มีคนดู', 'value' => number_format($viewedProducts), 'raw' => $viewedProducts, 'decimals' => 0, 'delta' => $this->delta($viewedProducts, $previousViewedProducts), 'note' => null, 'icon' => 'products'],
                ['label' => 'เฉลี่ยต่อวัน', 'value' => number_format($averagePerDay, 1), 'raw' => $averagePerDay, 'decimals' => 1, 'delta' => $this->delta($averagePerDay, $previousAveragePerDay), 'note' => null, 'icon' => 'average'],
                ['label' => 'สินค้ายอดนิยม', 'value' => $leader['label'] ?? '-', 'raw' => null, 'decimals' => 0, 'delta' => null, 'note' => $leader ? number_format($leader['clicks']).' คลิก · '.$leader['share'].'% ของทั้งหมด' : 'ยังไม่มีคลิกในช่วงนี้', 'icon' => 'star'],
            ],
            'productCount' => Product::query()->count(),
            'monthly' => $this->monthly($now),
            'categories' => $this->categories($start, $now, $clicks),
            'topProducts' => $topProducts,
            'heatmap' => $this->heatmap($start, $now),
            'unviewedProducts' => Product::query()
                ->with('category')
                ->whereDoesntHave('views', fn (Builder $query) => $query->whereBetween('viewed_at', [$start, $now]))
                ->orderBy('sort_order')
                ->get(),
            'recentViews' => ProductView::query()
                ->with('product.category')
                ->where('viewed_at', '<=', $now)
                ->latest('viewed_at')
                ->limit(6)
                ->get(),
        ]);
    }

    /**
     * @return Builder<ProductView>
     */
    private function viewsBetween(Carbon $from, Carbon $to): Builder
    {
        return ProductView::query()->where('viewed_at', '>=', $from)->where('viewed_at', '<', $to);
    }

    private function periodStart(string $period, Carbon $now): Carbon
    {
        $definition = self::PERIODS[$period];

        return isset($definition['days'])
            ? $now->copy()->subDays($definition['days'])
            : $now->copy()->subMonths($definition['months']);
    }

    private function delta(int|float $current, int|float $previous): ?float
    {
        if ($previous == 0) {
            return null;
        }

        return round(($current - $previous) / $previous * 100, 1);
    }

    /**
     * @return Collection<int, array{label: string, category: string, clicks: int, share: float}>
     */
    private function topProducts(Carbon $from, Carbon $to, int $total): Collection
    {
        return $this->viewsBetween($from, $to)
            ->selectRaw('product_id, count(*) as clicks')
            ->groupBy('product_id')
            ->orderByDesc('clicks')
            ->limit(5)
            ->with('product.category')
            ->get()
            ->filter(fn (ProductView $row) => $row->product !== null)
            ->map(fn (ProductView $row) => [
                'label' => $row->product->name,
                'category' => $row->product->category->name,
                'clicks' => (int) $row->clicks,
                'share' => $total > 0 ? round($row->clicks / $total * 100, 1) : 0.0,
            ])
            ->values();
    }

    /**
     * @return array{items: list<array{label: string, color: string, clicks: int, share: float}>, gradient: string}
     */
    private function categories(Carbon $from, Carbon $to, int $total): array
    {
        $counts = $this->viewsBetween($from, $to)
            ->join('products', 'products.id', '=', 'product_views.product_id')
            ->selectRaw('products.category_id, count(*) as clicks')
            ->groupBy('products.category_id')
            ->pluck('clicks', 'category_id');

        $items = [];
        $stops = [];
        $cursor = 0.0;

        foreach (Category::query()->orderBy('id')->get() as $index => $category) {
            $categoryClicks = (int) $counts->get($category->id, 0);
            $share = $total > 0 ? round($categoryClicks / $total * 100, 1) : 0.0;
            $color = self::CATEGORY_COLORS[$index % count(self::CATEGORY_COLORS)];

            $items[] = ['label' => $category->name, 'color' => $color, 'clicks' => $categoryClicks, 'share' => $share];

            if ($share > 0) {
                $stops[] = sprintf('%s %.2f%% %.2f%%', $color, $cursor, $cursor + $share);
                $cursor += $share;
            }
        }

        return [
            'items' => $items,
            'gradient' => $stops === [] ? '#e8e3da 0% 100%' : implode(', ', $stops),
        ];
    }

    /**
     * @return array{months: list<array{label: string, current: int, previous: int}>, max: int, ticks: list<int>, currentTotal: int, previousTotal: int, growth: ?float, currentPath: string, currentArea: string, previousPath: string, width: int, height: int}
     */
    private function monthly(Carbon $now): array
    {
        $totals = ProductView::query()
            ->where('viewed_at', '>=', $now->copy()->startOfMonth()->subMonths(23))
            ->where('viewed_at', '<=', $now)
            ->toBase()
            ->pluck('viewed_at')
            ->countBy(fn (string $viewedAt) => substr($viewedAt, 0, 7));

        $months = [];

        for ($offset = 11; $offset >= 0; $offset--) {
            $month = $now->copy()->startOfMonth()->subMonths($offset);

            $months[] = [
                'label' => $month->locale('th')->translatedFormat('M'),
                'current' => (int) $totals->get($month->format('Y-m'), 0),
                'previous' => (int) $totals->get($month->copy()->subYear()->format('Y-m'), 0),
            ];
        }

        $collection = collect($months);
        $axisMax = $this->niceAxisMax((int) $collection->max(fn (array $month) => max($month['current'], $month['previous'])));
        $currentTotal = (int) $collection->sum('current');
        $previousTotal = (int) $collection->sum('previous');
        $currentPoints = $this->chartPoints(array_column($months, 'current'), $axisMax);

        return [
            'months' => $months,
            'max' => $axisMax,
            'ticks' => array_map(fn (int $step) => intdiv($axisMax * $step, 4), [4, 3, 2, 1, 0]),
            'currentTotal' => $currentTotal,
            'previousTotal' => $previousTotal,
            'growth' => $this->delta($currentTotal, $previousTotal),
            'currentPath' => $this->smoothPath($currentPoints),
            'currentArea' => $this->smoothPath($currentPoints).sprintf(' L %.2f %d L 0 %d Z', self::CHART_WIDTH, self::CHART_HEIGHT, self::CHART_HEIGHT),
            'previousPath' => $this->smoothPath($this->chartPoints(array_column($months, 'previous'), $axisMax)),
            'width' => self::CHART_WIDTH,
            'height' => self::CHART_HEIGHT,
        ];
    }

    /**
     * @return array{days: list<array{label: string, slots: list<int>}>, slots: list<string>, max: int}
     */
    private function heatmap(Carbon $from, Carbon $to): array
    {
        $dayLabels = [1 => 'จ.', 2 => 'อ.', 3 => 'พ.', 4 => 'พฤ.', 5 => 'ศ.', 6 => 'ส.', 7 => 'อา.'];
        $counts = array_fill_keys(array_keys($dayLabels), array_fill(0, count(self::TIME_SLOTS), 0));

        foreach ($this->viewsBetween($from, $to)->toBase()->pluck('viewed_at') as $viewedAt) {
            $timestamp = strtotime($viewedAt);
            $hour = (int) date('G', $timestamp);

            foreach (self::TIME_SLOTS as $index => $slot) {
                if ($hour >= $slot['from'] && $hour <= $slot['to']) {
                    $counts[(int) date('N', $timestamp)][$index]++;

                    break;
                }
            }
        }

        $days = [];

        foreach ($dayLabels as $day => $label) {
            $days[] = ['label' => $label, 'slots' => $counts[$day]];
        }

        return [
            'days' => $days,
            'slots' => array_column(self::TIME_SLOTS, 'label'),
            'max' => max(1, ...array_merge(...array_values($counts))),
        ];
    }

    private function niceAxisMax(int $max): int
    {
        if ($max <= 0) {
            return 4;
        }

        $rawStep = $max / 4;
        $magnitude = 10 ** floor(log10($rawStep));

        foreach ([1, 2, 2.5, 5, 10] as $multiplier) {
            if ($multiplier * $magnitude >= $rawStep) {
                return (int) max(4, ceil($multiplier * $magnitude) * 4);
            }
        }

        return (int) (10 * $magnitude * 4);
    }

    /**
     * @param  list<int>  $values
     * @return list<array{0: float, 1: float}>
     */
    private function chartPoints(array $values, int $axisMax): array
    {
        $lastIndex = max(1, count($values) - 1);

        return array_map(
            fn (int $value, int $index) => [
                $index / $lastIndex * self::CHART_WIDTH,
                self::CHART_HEIGHT - ($value / $axisMax * self::CHART_HEIGHT),
            ],
            $values,
            array_keys($values),
        );
    }

    /**
     * Catmull-Rom curve through every point, converted to cubic Bézier segments.
     *
     * @param  list<array{0: float, 1: float}>  $points
     */
    private function smoothPath(array $points): string
    {
        $path = sprintf('M %.2f %.2f', $points[0][0], $points[0][1]);
        $count = count($points);
        $clamp = fn (float $y): float => max(0, min(self::CHART_HEIGHT, $y));

        for ($i = 0; $i < $count - 1; $i++) {
            $previous = $points[max(0, $i - 1)];
            $from = $points[$i];
            $to = $points[$i + 1];
            $next = $points[min($count - 1, $i + 2)];

            $path .= sprintf(
                ' C %.2f %.2f, %.2f %.2f, %.2f %.2f',
                $from[0] + ($to[0] - $previous[0]) / 6,
                $clamp($from[1] + ($to[1] - $previous[1]) / 6),
                $to[0] - ($next[0] - $from[0]) / 6,
                $clamp($to[1] - ($next[1] - $from[1]) / 6),
                $to[0],
                $to[1],
            );
        }

        return $path;
    }
}
