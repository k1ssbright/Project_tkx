<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function home(): View
    {
        $catalog = Product::query()->with('category')->orderBy('sort_order')->get();
        $featured = $catalog->where('is_featured', true)->take(4)->values();

        return view('home', [
            'featured' => $featured,
            'more' => $catalog->whereNotIn('id', $featured->pluck('id'))->take(8)->values(),
            'categories' => Category::query()->withCount('products')->orderBy('id')->get(),
            'grouped' => $catalog->groupBy('category_id'),
        ]);
    }

    public function news(): View
    {
        return view('news.index', [
            'articles' => Article::query()->latest()->latest('id')->paginate(9),
        ]);
    }

    public function article(Article $article): View
    {
        $article->load('images');

        return view('news.show', [
            'article' => $article,
            'others' => Article::query()->whereKeyNot($article->id)->latest()->latest('id')->limit(3)->get(),
        ]);
    }

    public function index(Request $request): View
    {
        $current = null;

        $products = Product::query()->with('category')->orderBy('sort_order');

        if ($request->filled('category')) {
            $current = Category::query()
                ->where('slug', $request->string('category'))
                ->firstOrFail();

            $products->where('category_id', $current->id);
        }

        return view('shop.index', [
            'products' => $products->get(),
            'categories' => Category::query()->withCount('products')->orderBy('id')->get(),
            'current' => $current,
        ]);
    }

    public function show(Request $request, Product $product): View
    {
        if (! $request->user()?->is_admin) {
            $product->views()->create(['viewed_at' => now()]);
        }

        $product->load('category');

        $related = Product::query()
            ->with('category')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('shop.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }

    public function about(): View
    {
        return view('about');
    }
}
