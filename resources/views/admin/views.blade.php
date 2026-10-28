@extends('layouts.admin')

@section('title', 'สถิติการเข้าชม')

@section('content')
    <section class="page-intro manage-intro">
        <div>
            <p class="eyebrow">Dashboard</p>
            <h1>สถิติการเข้าชมสินค้า</h1>
            <p class="lede">จำนวนครั้งที่ลูกค้ากดเข้าดูสินค้าแต่ละชิ้น ตั้งแต่ {{ $since->locale('th')->translatedFormat('j F Y') }} ถึงวันนี้ ไม่นับการเข้าดูของแอดมิน</p>
        </div>
    </section>

    <div class="period-tabs" role="navigation" aria-label="เลือกช่วงเวลา">
        @foreach ($periods as $key => $option)
            <a href="{{ route('admin.views', ['period' => $key]) }}" @class(['filter', 'is-active' => $period === $key])>{{ $option['label'] }}</a>
        @endforeach
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <span>เข้าชมสินค้า ({{ $periods[$period]['label'] }})</span>
            <strong>{{ number_format($totalViews) }}</strong>
        </div>
        <div class="stat-card">
            <span>สินค้าทั้งหมด</span>
            <strong>{{ number_format($productCount) }}</strong>
        </div>
        <div class="stat-card">
            <span>ข่าวสาร</span>
            <strong>{{ number_format($newsCount) }}</strong>
        </div>
        <div class="stat-card">
            <span>สมาชิก</span>
            <strong>{{ number_format($memberCount) }}</strong>
        </div>
    </div>

    @if ($products->isEmpty())
        <p class="empty">ยังไม่มีสินค้า</p>
    @else
        <div class="views-table-wrap">
            <table class="views-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">สินค้า</th>
                        <th scope="col">หมวดหมู่</th>
                        <th scope="col" class="views-col">จำนวนเข้าชม</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a class="views-product" href="{{ route('admin.products.edit', $product) }}">
                                    <img src="{{ $product->imageUrl() }}" alt="">
                                    <span>{{ $product->name }}</span>
                                </a>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td class="views-col">
                                <div class="views-bar">
                                    <span style="width: {{ round($product->views_count / $maxViews * 100) }}%"></span>
                                </div>
                                <strong>{{ number_format($product->views_count) }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
