@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <article class="product-detail">
        <div class="product-detail-gallery">
            <button class="product-detail-media" type="button" data-zoom-open>
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" fetchpriority="high">
            </button>
            <p class="image-zoom-note">กดที่รูปเพื่อดูขนาดเต็มตามไฟล์ต้นฉบับ</p>
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

            <p class="notice">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
                <span>หน้านี้สำหรับดูรายละเอียดสินค้า ไม่มีตะกร้าและไม่รับสั่งซื้อ</span>
            </p>

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

    <dialog class="image-zoom" data-zoom>
        <form method="dialog">
            <button type="submit">ปิด</button>
        </form>
        <img alt="{{ $product->name }}">
    </dialog>

    <script>
        const zoom = document.querySelector('[data-zoom]');
        const source = document.querySelector('[data-zoom-open] img');
        document.querySelector('[data-zoom-open]')?.addEventListener('click', () => {
            const preview = zoom?.querySelector('img');
            if (!zoom || !source || !preview) return;
            preview.src = source.currentSrc || source.src;
            zoom.showModal();
        });
    </script>
@endsection
