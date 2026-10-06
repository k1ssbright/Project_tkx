@extends('layouts.app')

@section('title', 'TKX')

@section('content')
    <section class="hero">
        <div class="hero-copy">
            <p class="eyebrow">White wardrobe</p>
            <h1>เสื้อผ้าและกางเกง<br>โทนขาว</h1>
            <p class="lede">คอลเลกชันผ้าลินินและคอตตอนสีขาว ครีม และเบจ ตัดเย็บเรียบ ใส่ซ้ำได้ทุกวันโดยไม่ต้องมีลาย</p>
            <a class="text-link" href="{{ route('shop.index') }}">ดูสินค้าทั้งหมด</a>
        </div>
        <div class="hero-media">
            <img src="{{ asset('images/hero.jpg') }}?v={{ filemtime(public_path('images/hero.jpg')) }}" alt="ภาพจากคอลเลกชัน TKX">
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <p class="eyebrow">หมวดหมู่</p>
            <h2>เลือกจากเสื้อหรือกางเกง</h2>
        </div>
        <div class="category-grid">
            @foreach ($categories as $category)
                <a class="category-tile" href="{{ route('shop.index', ['category' => $category->slug]) }}">
                    <span class="eyebrow">{{ $category->subtitle }}</span>
                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->description }}</p>
                    <span class="text-link">{{ $category->products_count }} ชิ้น</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="section">
        <div class="section-heading">
            <p class="eyebrow">แนะนำ</p>
            <h2>ชิ้นที่ใส่บ่อยในคอลเลกชันนี้</h2>
        </div>
        <div class="product-grid">
            @foreach ($featured as $featuredProduct)
                @include('partials.product-card', ['product' => $featuredProduct])
            @endforeach
        </div>
    </section>
@endsection
