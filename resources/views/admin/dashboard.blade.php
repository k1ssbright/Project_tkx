@extends('layouts.admin')

@section('title', 'ภาพรวมระบบและสถิติ')

@section('content')
    <div class="admin-container">
        <div class="admin-page-header">
            <div>
                <p class="eyebrow">Admin Dashboard</p>
                <h1>ภาพรวมระบบและสถิติ</h1>
                <p class="lede">สรุปข้อมูลสินค้า ยอดการเข้าชม และข่าวสารประชาสัมพันธ์</p>
            </div>
            <div class="admin-header-actions">
                <a href="{{ route('admin.products.create') }}" class="btn-primary">＋ เพิ่มสินค้าใหม่</a>
                <a href="{{ route('admin.articles.create') }}" class="btn-secondary">＋ เพิ่มข่าวสาร</a>
            </div>
        </div>

        <!-- Period Filter Selector -->
        <div class="analytics-filter-card">
            <div class="filter-header">
                <span class="filter-title">ช่วงเวลาแสดงยอดสถิติการเข้าชม:</span>
                <span class="filter-current-badge">ช่วงเวลาปัจจุบัน: <strong>{{ $periodLabel }}</strong></span>
            </div>
            <div class="period-tabs">
                <a href="{{ route('admin.dashboard', ['period' => '1m']) }}" @class(['period-tab', 'is-active' => $period === '1m'])>
                    1 เดือน
                </a>
                <a href="{{ route('admin.dashboard', ['period' => '3m']) }}" @class(['period-tab', 'is-active' => $period === '3m'])>
                    3 เดือน
                </a>
                <a href="{{ route('admin.dashboard', ['period' => '6m']) }}" @class(['period-tab', 'is-active' => $period === '6m'])>
                    6 เดือน
                </a>
                <a href="{{ route('admin.dashboard', ['period' => '1y']) }}" @class(['period-tab', 'is-active' => $period === '1y'])>
                    1 ปี
                </a>
                <a href="{{ route('admin.dashboard', ['period' => 'all']) }}" @class(['period-tab', 'is-active' => $period === 'all'])>
                    ทั้งหมด
                </a>
            </div>
        </div>

        <!-- Stats Metric Grid -->
        <div class="admin-stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👁️</div>
                <div class="stat-content">
                    <span class="stat-label">ยอดเข้าชมสินค้า ({{ $periodLabel }})</span>
                    <strong class="stat-value text-accent">{{ number_format($periodViewsCount) }}</strong>
                    <span class="stat-hint">ครั้งที่กดดูรายละเอียดสินค้า</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <div class="stat-content">
                    <span class="stat-label">สินค้าทั้งหมดในระบบ</span>
                    <strong class="stat-value">{{ number_format($totalProducts) }}</strong>
                    <span class="stat-hint">รายการในคลัง</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📰</div>
                <div class="stat-content">
                    <span class="stat-label">ข่าวสาร/บทความ</span>
                    <strong class="stat-value">{{ number_format($totalArticles) }}</strong>
                    <span class="stat-hint">เรื่องที่เผยแพร่แล้ว</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📈</div>
                <div class="stat-content">
                    <span class="stat-label">ยอดเข้าชมตลอดกาล</span>
                    <strong class="stat-value">{{ number_format($allTimeViewsCount) }}</strong>
                    <span class="stat-hint">ครั้งนับตั้งแต่เริ่มเปิดระบบ</span>
                </div>
            </div>
        </div>

        <!-- Two Column Content Grid -->
        <div class="admin-dashboard-grid">
            <!-- Left: Top Viewed Products -->
            <section class="admin-card">
                <div class="card-header">
                    <div>
                        <h2>🔥 สินค้าที่มีคนกดเข้าดูสูงสุด</h2>
                        <p class="card-subtitle">เรียงตามยอดเข้าชมในรอบ {{ $periodLabel }} (แสดงเฉพาะหลังบ้าน)</p>
                    </div>
                    <a href="{{ route('admin.products.index', ['period' => $period]) }}" class="card-link">ดูสินค้าทั้งหมด →</a>
                </div>

                @if ($topProducts->isEmpty())
                    <p class="empty-text">ยังไม่มีข้อมูลสินค้า</p>
                @else
                    <div class="top-products-list">
                        @foreach ($topProducts as $index => $product)
                            <div class="top-product-item">
                                <span class="rank-badge rank-{{ $index + 1 }}">{{ $index + 1 }}</span>
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="product-thumb">
                                <div class="product-info">
                                    <h3>{{ $product->name }}</h3>
                                    <span class="product-category">{{ $product->category->name }} · {{ $product->formattedPrice() }}</span>
                                </div>
                                <div class="product-views-badge">
                                    <span class="views-count">{{ number_format($product->views_count) }}</span>
                                    <span class="views-unit">วิว</span>
                                </div>
                                <a href="{{ route('products.show', $product) }}" target="_blank" class="btn-sm-view" title="ดูหน้าร้าน">↗</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Right: News / Articles Overview -->
            <section class="admin-card">
                <div class="card-header">
                    <div>
                        <h2>📰 ข่าวสารล่าสุด</h2>
                        <p class="card-subtitle">จัดการข่าวสารและกิจกรรมที่แสดงในหน้าร้าน</p>
                    </div>
                    <a href="{{ route('admin.articles.index') }}" class="card-link">จัดการทั้งหมด →</a>
                </div>

                @if ($recentArticles->isEmpty())
                    <p class="empty-text">ยังไม่มีข่าวสารในระบบ</p>
                @else
                    <div class="recent-articles-list">
                        @foreach ($recentArticles as $article)
                            <div class="recent-article-item">
                                <img src="{{ $article->imageUrl() }}" alt="{{ $article->title }}" class="article-thumb">
                                <div class="article-info">
                                    <h3>{{ $article->title }}</h3>
                                    <p>{{ Str::limit($article->excerpt, 90) }}</p>
                                    <span class="article-date">{{ $article->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="article-actions">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn-sm-edit">แก้ไข</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
