<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function home(): View
    {
        $catalog = Product::query()->with('category')->orderBy('sort_order')->get();

        return view('home', [
            'featured' => $catalog->where('is_featured', true)->take(4)->values(),
            'categories' => Category::query()->withCount('products')->orderBy('id')->get(),
            'grouped' => $catalog->groupBy('category_id'),
        ]);
    }

    public function articles(): View
    {
        return view('articles.index', [
            'articles' => Article::query()->orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function article(Article $article): View
    {
        return view('articles.show', [
            'article' => $article,
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

    public function show(Product $product): View
    {
        $product->load('category');

        $this->recordProductView($product);

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

    private function recordProductView(Product $product): void
    {
        $viewedKey = 'viewed_products';
        $viewed = session()->get($viewedKey, []);

        if (! in_array($product->id, $viewed, true)) {
            ProductView::query()->create([
                'product_id' => $product->id,
                'ip_address' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 255),
                'viewed_at' => now(),
            ]);

            session()->push($viewedKey, $product->id);
        }
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
    }
}
