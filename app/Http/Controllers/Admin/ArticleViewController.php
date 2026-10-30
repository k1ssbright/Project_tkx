<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleViewController extends Controller
{
    public function __invoke(Request $request): View
    {
        $request->validate([
            'period' => ['nullable', Rule::in(array_keys(DashboardController::PERIODS))],
        ]);

        $period = $request->string('period', '30d')->toString();
        $definition = DashboardController::PERIODS[$period];
        $now = now();
        $start = isset($definition['days'])
            ? $now->copy()->subDays($definition['days'])
            : $now->copy()->subMonths($definition['months']);
        $previousStart = $start->copy()->sub($start->diff($now));
        $days = max(1, (int) round($start->diffInDays($now)));

        $articles = Article::query()
            ->withCount([
                'views',
                'views as period_views_count' => fn (Builder $query) => $query->whereBetween('viewed_at', [$start, $now]),
                'views as previous_views_count' => fn (Builder $query) => $query->where('viewed_at', '>=', $previousStart)->where('viewed_at', '<', $start),
            ])
            ->orderByDesc('period_views_count')
            ->latest()
            ->latest('id')
            ->get();

        $views = (int) $articles->sum('period_views_count');
        $previousViews = (int) $articles->sum('previous_views_count');
        $readArticles = $articles->where('period_views_count', '>', 0)->count();
        $previousReadArticles = $articles->where('previous_views_count', '>', 0)->count();
        $averagePerDay = round($views / $days, 1);
        $leader = $articles->firstWhere('period_views_count', '>', 0);

        return view('admin.news.views', [
            'periods' => DashboardController::PERIODS,
            'period' => $period,
            'start' => $start,
            'articles' => $articles,
            'views' => $views,
            'previousViews' => $previousViews,
            'viewsDelta' => $this->delta($views, $previousViews),
            'readArticles' => $readArticles,
            'readArticlesDelta' => $this->delta($readArticles, $previousReadArticles),
            'averagePerDay' => $averagePerDay,
            'averagePerDayDelta' => $this->delta($averagePerDay, round($previousViews / $days, 1)),
            'leader' => $leader,
            'leaderShare' => $leader && $views > 0 ? round($leader->period_views_count / $views * 100, 1) : 0,
            'maxViews' => max(1, (int) $articles->max('period_views_count')),
            'allTimeViews' => (int) $articles->sum('views_count'),
            'trend' => $this->trend($start, $now, ! isset($definition['days'])),
        ]);
    }

    private function delta(int|float $current, int|float $previous): ?float
    {
        if ($previous == 0) {
            return null;
        }

        return round(($current - $previous) / $previous * 100, 1);
    }

    /**
     * @return array{buckets: list<array{label: string, views: int, showLabel: bool}>, max: int}
     */
    private function trend(Carbon $start, Carbon $now, bool $byMonth): array
    {
        $keyFormat = $byMonth ? 'Y-m' : 'Y-m-d';

        $counts = ArticleView::query()
            ->whereBetween('viewed_at', [$start, $now])
            ->pluck('viewed_at')
            ->countBy(fn (mixed $viewedAt): string => Carbon::parse($viewedAt)->format($keyFormat));

        $cursor = $byMonth ? $start->copy()->startOfMonth() : $start->copy()->startOfDay();
        $total = (int) ($byMonth ? $cursor->diffInMonths($now) : $cursor->diffInDays($now)) + 1;
        $labelEvery = $total > 14 ? 5 : 1;
        $buckets = [];

        for ($index = 0; $index < $total; $index++) {
            $buckets[] = [
                'label' => $cursor->locale('th')->translatedFormat($byMonth ? 'M y' : 'j M'),
                'views' => (int) $counts->get($cursor->format($keyFormat), 0),
                'showLabel' => $index % $labelEvery === 0 || $index === $total - 1,
            ];

            $byMonth ? $cursor->addMonthNoOverflow() : $cursor->addDay();
        }

        return [
            'buckets' => $buckets,
            'max' => max(1, ...array_column($buckets, 'views')),
        ];
    }
}
