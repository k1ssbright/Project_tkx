@extends('layouts.app')

@section('title', 'NANO CIRCUIT')

@section('content')
    <section class="billboard">
        <img src="{{ asset('images/hero-wardrobe.jpg') }}?v={{ filemtime(public_path('images/hero-wardrobe.jpg')) }}" alt="ราวแขวนเสื้อเชิ้ตลินิน เสื้อโปโล เสื้อยืด และกางเกงโทนขาวครีม">
        <div class="billboard-copy">
            <p class="billboard-eyebrow">New arrival · White wardrobe</p>
            <h1>เสื้อผ้าและกางเกง<br>โทนขาว</h1>
            <p class="billboard-lede">ลินินและคอตตอนสีขาว ครีม และเบจ ตัดเย็บเรียบ ใส่ซ้ำได้ทุกวัน</p>
            <div class="billboard-actions">
                <a class="buy-now" href="{{ route('shop.index') }}">ดูสินค้าทั้งหมด</a>
                <a class="billboard-link" href="{{ route('news.index') }}">อ่านข่าวสาร</a>
            </div>
        </div>
    </section>

    <section class="values" aria-label="จุดเด่นของร้าน">
        <div class="value">
            <span>01</span>
            <h2>ลินินและคอตตอน</h2>
            <p>เนื้อผ้าระบายอากาศดี ใส่สบายในอากาศร้อน</p>
        </div>
        <div class="value">
            <span>02</span>
            <h2>โทนขาว ครีม เบจ</h2>
            <p>ทุกชิ้นจับคู่กันได้ ไม่ต้องคิดเรื่องสีให้ยุ่งยาก</p>
        </div>
        <div class="value">
            <span>03</span>
            <h2>ตัดเย็บเรียบ</h2>
            <p>ไม่มีลาย ไม่มีโลโก้ใหญ่ ใส่ซ้ำได้ทุกวัน</p>
        </div>
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
