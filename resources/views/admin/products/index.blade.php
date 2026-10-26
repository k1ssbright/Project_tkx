@extends('layouts.app')

@section('title', 'จัดการสินค้า')

@section('content')
    <section class="page-intro manage-intro">
        <div>
            <p class="eyebrow">Catalog</p>
            <h1>จัดการสินค้า</h1>
            <p class="lede">เพิ่ม แก้ไข หรือลบสินค้าในคอลเลกชัน รูปที่อัปโหลดจะไปแทนรูปเดิมทันที</p>
        </div>
        <a class="text-link" href="{{ route('products.manage.create') }}">เพิ่มสินค้า</a>
    </section>

    @if (session('status'))
        <p class="manage-status">{{ session('status') }}</p>
    @endif

    @if ($products->isEmpty())
        <p class="empty">ยังไม่มีสินค้า</p>
    @else
        <div class="manage-list">
            @foreach ($products as $product)
                <article class="manage-item">
                    <img src="{{ $product->imageUrl() }}" alt="">
                    <div>
                        <h2>{{ $product->name }}</h2>
                        <p>{{ $product->category->name }} · {{ $product->formattedPrice() }}</p>
                    </div>
                    <div class="manage-actions">
                        <a href="{{ route('products.manage.edit', $product) }}">แก้ไข</a>
                        <form method="post" action="{{ route('products.manage.destroy', $product) }}" onsubmit="return confirm('ลบสินค้านี้ใช่ไหม')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">ลบ</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
