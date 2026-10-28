<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductViewController extends Controller
{
    /**
     * @var array<string, array{label: string, months: int}>
     */
    public const array PERIODS = [
        '1m' => ['label' => '1 เดือน', 'months' => 1],
        '3m' => ['label' => '3 เดือน', 'months' => 3],
        '6m' => ['label' => '6 เดือน', 'months' => 6],
        '12m' => ['label' => '1 ปี', 'months' => 12],
    ];

    public function __invoke(Request $request): View
    {
        $request->validate([
            'period' => ['nullable', Rule::in(array_keys(self::PERIODS))],
        ]);

        $period = $request->string('period', '1m')->toString();
        $since = now()->subMonths(self::PERIODS[$period]['months']);

        $products = Product::query()
            ->with('category')
            ->withCount(['views' => fn (Builder $query) => $query->where('viewed_at', '>=', $since)])
            ->orderByDesc('views_count')
            ->orderBy('sort_order')
            ->get();

        return view('admin.views', [
            'periods' => self::PERIODS,
            'period' => $period,
            'since' => $since,
            'products' => $products,
            'totalViews' => $products->sum('views_count'),
            'maxViews' => max(1, (int) $products->max('views_count')),
            'productCount' => $products->count(),
            'newsCount' => Article::query()->count(),
            'memberCount' => User::query()->where('is_admin', false)->count(),
        ]);
    }
}
