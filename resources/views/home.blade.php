@extends('layouts.app')

@section('title', 'Nano Circuit')

@section('content')
    <section class="billboard">
        <img src="{{ asset('images/nanocircuit-logo') }}?v={{ filemtime(public_path('images/hero.jpg')) }}" alt="ภาพจากคอลเลกชัน TKX">
        <div class="billboard-copy">
            <p>New arrival</p>
            <h1>เสื้อผ้าและกางเกง<br>โทนขาว</h1>
            <a class="buy-now" href="{{ route('shop.index') }}">ดูสินค้า</a>
        </div>
    </section>

    <section class="campaign">
        <p class="eyebrow">White wardrobe</p>
        <h2>เสื้อผ้าและกางเกงโทนขาว</h2>
        <p class="lede">คอลเลกชันผ้าลินินและคอตตอนสีขาว ครีม และเบจ ตัดเย็บเรียบ ใส่ซ้ำได้ทุกวันโดยไม่ต้องมีลาย</p>
    </section>

    <section class="shelf" id="new">
        <div class="shelf-head">
            <h2>สินค้าใหม่</h2>
            <a href="{{ route('shop.index') }}">ดูทั้งหมด</a>
        </div>
        <div class="product-grid">
            @foreach ($featured as $featuredProduct)
                @include('partials.product-card', ['product' => $featuredProduct])
            @endforeach
        </div>
    </section>

    <section class="articles" id="articles">
        <div class="articles-head">
            <h2>บทความ</h2>
        </div>
        <div class="article-grid">
            @foreach ($articles as $article)
                <article class="article-card">
                    <a href="{{ route('articles.show', $article) }}">
                        <img src="{{ $article->imageUrl() }}" alt="">
                        <h3>{{ $article->title }}</h3>
                    </a>
                    <p>{{ $article->excerpt }}</p>
                    <a class="read-more" href="{{ route('articles.show', $article) }}">อ่านต่อ</a>
                </article>
            @endforeach
        </div>
    </section>

    <section class="banner-row">
        @foreach ($categories as $category)
            @php $cover = $grouped->get($category->id, collect())->first(); @endphp
            <a class="banner" href="{{ route('shop.index', ['category' => $category->slug]) }}">
                @if ($cover)
                    <img src="{{ $cover->imageUrl() }}" alt="">
                @endif
                <span>
                    <small>{{ $category->subtitle }}</small>
                    <strong>{{ $category->name }}</strong>
                    ดูสินค้า
                </span>
            </a>
        @endforeach
    </section>

    @foreach ($categories as $category)
        @php $items = $grouped->get($category->id, collect())->take(4); @endphp
        @if ($items->isNotEmpty())
            <section class="shelf">
                <div class="shelf-head">
                    <h2>{{ $category->name }}</h2>
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}">ดูทั้งหมด</a>
                </div>
                <div class="product-grid">
                    @foreach ($items as $item)
                        @include('partials.product-card', ['product' => $item])
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach
@endsection
