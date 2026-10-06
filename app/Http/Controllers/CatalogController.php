<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'featured' => Product::query()
                ->with('category')
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->get(),
            'categories' => Category::query()->withCount('products')->orderBy('id')->get(),
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
