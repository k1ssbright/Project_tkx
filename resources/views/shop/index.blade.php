@extends('layouts.app')

@section('title', $current?->name ?? 'สินค้าทั้งหมด')

@section('content')
    <section class="page-intro">
        <p class="eyebrow">{{ $current?->subtitle ?? 'Catalog' }}</p>
        <h1>{{ $current?->name ?? 'สินค้าทั้งหมด' }}</h1>
        <p class="lede">{{ $current?->description ?? 'เสื้อและกางเกงโทนขาว ครีม และเบจ ทั้งคอลเลกชัน' }}</p>
        <div class="filters" role="navigation" aria-label="กรองหมวดหมู่">
            <a href="{{ route('shop.index') }}" @class(['filter', 'is-active' => $current === null])>ทั้งหมด</a>
            @foreach ($categories as $category)
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" @class(['filter', 'is-active' => $current?->is($category)])>
                    {{ $category->name }}
                    <span>{{ $category->products_count }}</span>
                </a>
            @endforeach
            <p class="result-count">{{ $products->count() }} รายการ</p>
        </div>
    </section>

    @if ($products->isEmpty())
        <p class="empty">ยังไม่มีสินค้าในหมวดนี้</p>
    @else
        <div class="product-grid">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
@endsection
