@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <article class="product-detail">
        <div class="product-detail-media">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
        </div>
        <div class="product-detail-copy">
            <p class="breadcrumb">
                <a href="{{ route('home') }}">หน้าแรก</a>
                <span>/</span>
                <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
            </p>
            <p class="eyebrow">{{ $product->category->subtitle }}</p>
            <h1>{{ $product->name }}</h1>
            <p class="price">{{ $product->formattedPrice() }}</p>
            <p class="lede">{{ $product->description }}</p>

            <dl class="spec">
                <div>
                    <dt>ผ้า</dt>
                    <dd>{{ $product->fabric }}</dd>
                </div>
                <div>
                    <dt>สี</dt>
                    <dd>
                        @foreach ($product->colors as $color)
                            <span class="chip">{{ $color }}</span>
                        @endforeach
                    </dd>
                </div>
                <div>
                    <dt>ไซส์</dt>
                    <dd>
                        @foreach ($product->sizes as $size)
                            <span class="chip">{{ $size }}</span>
                        @endforeach
                    </dd>
                </div>
            </dl>

            <p class="notice">หน้านี้สำหรับโชว์สินค้า สอบถามไซส์และจำนวนที่มีได้ที่ร้าน</p>

            @if ($product->category->slug === 'shirts')
                @include('partials.shirt-size-chart')
            @endif
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="section">
            <div class="section-heading">
                <p class="eyebrow">ในหมวดเดียวกัน</p>
                <h2>{{ $product->category->name }}ชิ้นอื่น</h2>
            </div>
            <div class="product-grid">
                @foreach ($related as $relatedProduct)
                    @include('partials.product-card', ['product' => $relatedProduct])
                @endforeach
            </div>
        </section>
    @endif
@endsection
