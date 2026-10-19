<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->query('period', '1m');

        $startDate = match ($period) {
            '3m' => now()->subMonths(3),
            '6m' => now()->subMonths(6),
            '1y' => now()->subYear(),
            'all' => null,
            default => now()->subMonth(), // 1m
        };

        $periodLabel = match ($period) {
            '3m' => '3 เดือนที่ผ่านมา',
            '6m' => '6 เดือนที่ผ่านมา',
            '1y' => '1 ปีที่ผ่านมา',
            'all' => 'ทั้งหมด',
            default => '1 เดือนที่ผ่านมา',
        };

        $totalProducts = Product::query()->count();
        $totalArticles = Article::query()->count();

        $periodViewsCount = ProductView::query()
            ->when($startDate, fn ($query) => $query->where('viewed_at', '>=', $startDate))
            ->count();

        $allTimeViewsCount = ProductView::query()->count();

        $topProducts = Product::query()
            ->with('category')
            ->withCount(['views' => function ($query) use ($startDate) {
                if ($startDate) {
                    $query->where('viewed_at', '>=', $startDate);
                }
            }])
            ->orderByDesc('views_count')
            ->orderBy('sort_order')
            ->take(5)
            ->get();

        $recentArticles = Article::query()
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('admin.dashboard', [
            'period' => $period,
            'periodLabel' => $periodLabel,
            'totalProducts' => $totalProducts,
            'totalArticles' => $totalArticles,
            'periodViewsCount' => $periodViewsCount,
            'allTimeViewsCount' => $allTimeViewsCount,
            'topProducts' => $topProducts,
            'recentArticles' => $recentArticles,
        ]);
    }
}
