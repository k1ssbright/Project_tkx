@extends('layouts.app')

@section('title', 'Nano Circuit')

@section('content')
    <section class="billboard">
        <img src="{{ $siteImages['hero_image'] }}" alt="ราวแขวนเสื้อเชิ้ตลินิน เสื้อโปโล เสื้อยืด และกางเกงโทนขาวครีม">
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

    @if ($featured->isNotEmpty())
        <section class="shelf" id="new">
            <div class="shelf-head">
                <div>
                    <p class="eyebrow">New arrival</p>
                    <h2>สินค้าใหม่</h2>
                </div>
                <a class="arrow-link" href="{{ route('shop.index') }}">ดูทั้งหมด @include('partials.icon-arrow')</a>
            </div>
            <div class="product-grid">
                @foreach ($featured as $featuredProduct)
                    @include('partials.product-card', ['product' => $featuredProduct])
                @endforeach
            </div>
        </section>
    @endif

    <section class="banner-row" aria-label="หมวดหมู่สินค้า">
        @foreach ($categories as $category)
            @php $cover = $grouped->get($category->id, collect())->first(fn ($product) => $product->hasImageFile()); @endphp
            <a class="banner" href="{{ route('shop.index', ['category' => $category->slug]) }}">
                @if ($cover)
                    <img src="{{ $cover->imageUrl() }}" alt="" loading="lazy">
                @endif
                <span>
                    <small>{{ $category->subtitle }}</small>
                    <strong>{{ $category->name }}</strong>
                    <em>ดูสินค้า {{ $category->products_count }} รายการ @include('partials.icon-arrow')</em>
                </span>
            </a>
        @endforeach
    </section>

    @if ($more->isNotEmpty())
        <section class="shelf">
            <div class="shelf-head">
                <div>
                    <p class="eyebrow">Collection</p>
                    <h2>เลือกชมเพิ่มเติม</h2>
                </div>
                <a class="arrow-link" href="{{ route('shop.index') }}">ดูสินค้าทั้งหมด @include('partials.icon-arrow')</a>
            </div>
            <div class="product-grid">
                @foreach ($more as $item)
                    @include('partials.product-card', ['product' => $item])
                @endforeach
            </div>
        </section>
    @endif

    <section class="closing-band">
        <p class="eyebrow">White wardrobe</p>
        <h2>ตู้เสื้อผ้าโทนขาว<br>ที่ใส่ได้ทุกวัน</h2>
        <p>เลือกเสื้อและกางเกงที่จับคู่กันได้ทุกชิ้น ดูรายละเอียดผ้า สี และไซส์ได้ในหน้าสินค้า</p>
        <div class="billboard-actions">
            <a class="buy-now" href="{{ route('shop.index') }}">ดูสินค้าทั้งหมด</a>
            <a class="billboard-link" href="{{ route('about') }}">เกี่ยวกับเรา</a>
        </div>
    </section>
@endsection
