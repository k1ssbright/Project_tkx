@extends('layouts.admin')

@section('title', 'จัดการสินค้าและยอดเข้าชม')

@section('content')
    <div class="admin-container">
        <div class="admin-page-header">
            <div>
                <p class="eyebrow">Inventory & Analytics</p>
                <h1>จัดการสินค้าและยอดเข้าชม</h1>
                <p class="lede">ตรวจสอบจำนวนการกดเข้าดูสินค้าแต่ละชิ้น (แสดงเฉพาะหลังบ้าน) และจัดการเพิ่ม/แก้ไข/ลบสินค้า</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-primary">＋ เพิ่มสินค้าใหม่</a>
        </div>

        <!-- Period Filter Selector -->
        <div class="analytics-filter-card">
            <div class="filter-header">
                <div>
                    <span class="filter-title">🔍 เลือกรอบระยะเวลาดูสถิติยอดกดเข้าชม:</span>
                    <span class="filter-summary">ยอดเข้าชมรวมทุกชิ้นในรอบนี้: <strong class="text-accent">{{ number_format($totalViews) }} ครั้ง</strong></span>
                </div>
                <span class="filter-current-badge">รอบปัจจุบัน: <strong>{{ $periodLabel }}</strong></span>
            </div>
            <div class="period-tabs">
                <a href="{{ route('admin.products.index', ['period' => '1m']) }}" @class(['period-tab', 'is-active' => $period === '1m'])>
                    1 เดือน
                </a>
                <a href="{{ route('admin.products.index', ['period' => '3m']) }}" @class(['period-tab', 'is-active' => $period === '3m'])>
                    3 เดือน
                </a>
                <a href="{{ route('admin.products.index', ['period' => '6m']) }}" @class(['period-tab', 'is-active' => $period === '6m'])>
                    6 เดือน
                </a>
                <a href="{{ route('admin.products.index', ['period' => '1y']) }}" @class(['period-tab', 'is-active' => $period === '1y'])>
                    1 ปี
                </a>
                <a href="{{ route('admin.products.index', ['period' => 'all']) }}" @class(['period-tab', 'is-active' => $period === 'all'])>
                    ทั้งหมด
                </a>
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="admin-card text-center" style="padding: 48px 24px;">
                <p class="empty-text">ยังไม่มีสินค้าในระบบ</p>
                <a href="{{ route('admin.products.create') }}" class="btn-primary" style="margin-top: 16px;">เพิ่มสินค้าชิ้นแรก</a>
            </div>
        @else
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">รูปภาพ</th>
                            <th>ชื่อสินค้า / หมวดหมู่</th>
                            <th>ราคา</th>
                            <th>ชนิดผ้า / สี / ไซส์</th>
                            <th class="text-center" style="min-width: 140px;">
                                👁️ ยอดกดเข้าดู<br>
                                <small class="text-accent">({{ $periodLabel }})</small>
                            </th>
                            <th class="text-right" style="width: 180px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="table-img">
                                </td>
                                <td>
                                    <div class="table-product-title">
                                        <strong>{{ $product->name }}</strong>
                                        @if ($product->is_featured)
                                            <span class="badge-featured">แนะนำ</span>
                                        @endif
                                    </div>
                                    <span class="table-subtext">{{ $product->category->name }}</span>
                                </td>
                                <td>
                                    <span class="table-price">{{ $product->formattedPrice() }}</span>
                                </td>
                                <td>
                                    <div class="table-subtext">ผ้า: {{ $product->fabric }}</div>
                                    <div class="table-subtext">สี: {{ implode(', ', $product->colors ?? []) }}</div>
                                    <div class="table-subtext">ไซส์: {{ implode(', ', $product->sizes ?? []) }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="views-badge-cell">
                                        <span class="views-badge-number">{{ number_format($product->views_count) }}</span>
                                        <span class="views-badge-label">ครั้ง</span>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="table-actions">
                                        <a href="{{ route('products.show', $product) }}" target="_blank" class="btn-action-view" title="ดูหน้าร้าน">หน้าร้าน</a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn-action-edit">แก้ไข</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="ลบสินค้านี้ใช่ไหม" data-confirm-button="ลบ">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete">ลบ</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
