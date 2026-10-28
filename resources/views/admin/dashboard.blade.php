@extends('layouts.admin')

@section('title', 'ภาพรวมการเข้าชม')

@php
    $shortNumber = fn (int $amount): string => match (true) {
        $amount >= 1000000 => number_format($amount / 1000000, 2).'M',
        $amount >= 10000 => number_format($amount / 1000, $amount >= 100000 ? 0 : 1).'k',
        default => number_format($amount),
    };
@endphp

@section('content')
    <section class="dash">
        <header class="dash-head">
            <div>
                <p class="eyebrow">Dashboard</p>
                <h1>ภาพรวมการเข้าชมสินค้า</h1>
                <p class="dash-sub">จำนวนครั้งที่ลูกค้ากดเข้าดูสินค้า ตั้งแต่ {{ $start->locale('th')->translatedFormat('j M Y') }} ถึงวันนี้ ไม่นับการเข้าดูของแอดมิน</p>
            </div>
            <nav class="dash-tabs" aria-label="เลือกช่วงเวลา">
                @foreach ($periods as $key => $option)
                    <a href="{{ route('admin.dashboard', ['period' => $key]) }}" @class(['is-active' => $period === $key]) @if ($period === $key) aria-current="page" @endif>{{ $option['label'] }}</a>
                @endforeach
            </nav>
        </header>

        @if ($sampleCount > 0)
            <p class="dash-notice">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
                <span>ตอนนี้มีคลิกตัวอย่าง (mock) {{ number_format($sampleCount) }} รายการปนอยู่ในกราฟ ลบออกได้ด้วยคำสั่ง <code>php artisan views:clear-samples</code> คลิกจริงจะไม่ถูกลบ</span>
            </p>
        @endif

        @if (! $hasViews)
            <p class="empty">ยังไม่มีคนกดเข้าดูสินค้า</p>
        @else
            <div class="kpi-grid">
                @foreach ($kpis as $kpi)
                    <article class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-label">{{ $kpi['label'] }}</span>
                            <span class="kpi-icon">
                                @switch($kpi['icon'])
                                    @case('clicks')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 9l5 12 1.8-5.2L21 14Z"/><path d="M7.2 2.2 8 5.1"/><path d="m5.1 8-2.9-.8"/><path d="M14 4.1 12 6"/><path d="m6 12-1.9 2"/></svg>
                                        @break
                                    @case('products')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        @break
                                    @case('average')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M8 15h2M14 15h2"/></svg>
                                        @break
                                    @default
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1Z"/></svg>
                                @endswitch
                            </span>
                        </div>
                        @if ($kpi['raw'] === null)
                            <strong class="kpi-value kpi-value-text">{{ $kpi['value'] }}</strong>
                        @else
                            <strong class="kpi-value" data-count="{{ $kpi['raw'] }}" data-decimals="{{ $kpi['decimals'] }}">{{ $kpi['value'] }}</strong>
                        @endif
                        @if ($kpi['note'] !== null)
                            <span class="kpi-delta is-flat">{{ $kpi['note'] }}</span>
                        @elseif ($kpi['delta'] === null)
                            <span class="kpi-delta is-flat">ไม่มีข้อมูลช่วงก่อนหน้า</span>
                        @else
                            <span @class(['kpi-delta', 'is-up' => $kpi['delta'] > 0, 'is-down' => $kpi['delta'] < 0, 'is-flat' => $kpi['delta'] == 0])>
                                <b>
                                    @if ($kpi['delta'] > 0)
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 15 6-6 6 6"/></svg>
                                    @elseif ($kpi['delta'] < 0)
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                                    @endif
                                    {{ $kpi['delta'] > 0 ? '+' : '' }}{{ $kpi['delta'] }}%
                                </b>
                                เทียบช่วงก่อนหน้า
                            </span>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="traffic-strip">
                <span>สินค้าในร้าน <b>{{ number_format($productCount) }}</b> รายการ</span>
                <span>ยังไม่มีคนดูในช่วงนี้ <b>{{ number_format($unviewedProducts->count()) }}</b> รายการ</span>
                <a class="arrow-link" href="{{ route('admin.views') }}">ดูยอดคลิกรายสินค้าทั้งหมด @include('partials.icon-arrow')</a>
            </div>

            <div class="dash-grid dash-grid-wide">
                <section class="dash-card">
                    <header class="dash-card-head">
                        <div>
                            <h2>คลิกเข้าชมรายเดือน</h2>
                            <p>12 เดือนล่าสุด เทียบกับเดือนเดียวกันปีก่อน</p>
                        </div>
                        <ul class="chart-legend">
                            <li><i class="legend-line legend-current"></i>ปีนี้</li>
                            <li><i class="legend-line legend-previous"></i>ปีก่อน</li>
                        </ul>
                    </header>

                    <div class="chart-stats">
                        <div>
                            <strong>{{ $shortNumber($monthly['currentTotal']) }}</strong>
                            <span>คลิก 12 เดือน</span>
                        </div>
                        <div>
                            <strong>{{ $shortNumber($monthly['previousTotal']) }}</strong>
                            <span>ช่วงเดียวกันปีก่อน</span>
                        </div>
                        <div>
                            @if ($monthly['growth'] === null)
                                <strong>-</strong>
                            @else
                                <strong @class(['is-up' => $monthly['growth'] > 0, 'is-down' => $monthly['growth'] < 0])>{{ $monthly['growth'] > 0 ? '+' : '' }}{{ $monthly['growth'] }}%</strong>
                            @endif
                            <span>เติบโตจากปีก่อน</span>
                        </div>
                    </div>

                    <div class="line-chart" role="img" aria-label="กราฟเส้นจำนวนคลิกเข้าชมรายเดือน 12 เดือนล่าสุดเทียบกับปีก่อน">
                        <div class="line-axis" aria-hidden="true">
                            @foreach ($monthly['ticks'] as $tick)
                                <span>{{ $shortNumber($tick) }}</span>
                            @endforeach
                        </div>
                        <div class="line-plot">
                            <svg viewBox="0 0 {{ $monthly['width'] }} {{ $monthly['height'] }}" preserveAspectRatio="none" aria-hidden="true">
                                <defs>
                                    <linearGradient id="views-area" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#fe7b07" stop-opacity="0.22"/>
                                        <stop offset="100%" stop-color="#fe7b07" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                @foreach ([0, 0.25, 0.5, 0.75] as $gridLine)
                                    <line class="line-grid" x1="0" x2="{{ $monthly['width'] }}" y1="{{ $monthly['height'] * $gridLine }}" y2="{{ $monthly['height'] * $gridLine }}"/>
                                @endforeach
                                <line class="line-baseline" x1="0" x2="{{ $monthly['width'] }}" y1="{{ $monthly['height'] }}" y2="{{ $monthly['height'] }}"/>
                                <g class="line-series">
                                    <path class="line-area" d="{{ $monthly['currentArea'] }}"/>
                                    <path class="line-previous" d="{{ $monthly['previousPath'] }}"/>
                                    <path class="line-current" d="{{ $monthly['currentPath'] }}"/>
                                </g>
                            </svg>
                            <div class="line-hover">
                                @foreach ($monthly['months'] as $month)
                                    <div class="line-col" style="--x: {{ round($loop->index / max(1, count($monthly['months']) - 1) * 100, 4) }}%; --y: {{ round(100 - $month['current'] / $monthly['max'] * 100, 2) }}%; --y-prev: {{ round(100 - $month['previous'] / $monthly['max'] * 100, 2) }}%">
                                        <i class="line-dot line-dot-current"></i>
                                        <i class="line-dot line-dot-previous"></i>
                                        <div @class(['line-tip', 'is-left' => $loop->index >= 8])>
                                            <b>{{ $month['label'] }}</b>
                                            <span><i class="legend-line legend-current"></i>ปีนี้ {{ number_format($month['current']) }} คลิก</span>
                                            <span><i class="legend-line legend-previous"></i>ปีก่อน {{ number_format($month['previous']) }} คลิก</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="line-labels" aria-hidden="true">
                                @foreach ($monthly['months'] as $month)
                                    <span style="--x: {{ round($loop->index / max(1, count($monthly['months']) - 1) * 100, 4) }}%">{{ $month['label'] }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <table class="sr-only">
                        <caption>คลิกเข้าชมรายเดือน</caption>
                        <thead><tr><th>เดือน</th><th>ปีนี้</th><th>ปีก่อน</th></tr></thead>
                        <tbody>
                            @foreach ($monthly['months'] as $month)
                                <tr><td>{{ $month['label'] }}</td><td>{{ number_format($month['current']) }}</td><td>{{ number_format($month['previous']) }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>

                <section class="dash-card">
                    <header class="dash-card-head">
                        <div>
                            <h2>คลิกตามหมวดสินค้า</h2>
                            <p>สัดส่วนในช่วงที่เลือก</p>
                        </div>
                    </header>
                    <div class="donut" style="--donut: conic-gradient({{ $categories['gradient'] }})" role="img" aria-label="สัดส่วนคลิกเข้าชมตามหมวดสินค้า">
                        <div class="donut-center">
                            <small>คลิกรวม</small>
                            <strong>{{ $shortNumber(collect($categories['items'])->sum('clicks')) }}</strong>
                        </div>
                    </div>
                    <ul class="donut-legend">
                        @foreach ($categories['items'] as $category)
                            <li>
                                <i class="swatch" style="background: {{ $category['color'] }}"></i>
                                <span>{{ $category['label'] }}</span>
                                <small>{{ number_format($category['clicks']) }} คลิก</small>
                                <b>{{ $category['share'] }}%</b>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>

            <div class="dash-grid">
                <section class="dash-card">
                    <header class="dash-card-head">
                        <div>
                            <h2>สินค้าที่มีคนดูมากที่สุด</h2>
                            <p>5 อันดับในช่วงที่เลือก</p>
                        </div>
                    </header>
                    @if ($topProducts->isEmpty())
                        <p class="dash-empty">ยังไม่มีคลิกในช่วงนี้</p>
                    @else
                        <ol class="rank-list">
                            @foreach ($topProducts as $item)
                                <li>
                                    <span class="rank-no">{{ $loop->iteration }}</span>
                                    <div class="rank-body">
                                        <div class="rank-line">
                                            <span>{{ $item['label'] }}</span>
                                            <b>{{ number_format($item['clicks']) }} คลิก</b>
                                        </div>
                                        <div class="rank-bar"><span style="width: {{ $item['share'] }}%"></span></div>
                                        <small>{{ $item['category'] }} · {{ $item['share'] }}% ของคลิกทั้งหมด</small>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </section>

                <section class="dash-card">
                    <header class="dash-card-head">
                        <div>
                            <h2>ช่วงเวลาที่คนเข้าดู</h2>
                            <p>จำนวนคลิกแยกตามวันและช่วงเวลา</p>
                        </div>
                    </header>
                    <div class="heatmap-scroll">
                        <table class="heatmap">
                            <thead>
                                <tr>
                                    <th scope="col"><span class="sr-only">วัน</span></th>
                                    @foreach ($heatmap['slots'] as $slot)
                                        <th scope="col">{{ $slot }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($heatmap['days'] as $day)
                                    <tr>
                                        <th scope="row">{{ $day['label'] }}</th>
                                        @foreach ($day['slots'] as $count)
                                            @php $intensity = $count / $heatmap['max']; @endphp
                                            <td @class(['is-strong' => $intensity > 0.55]) style="background: rgba(254, 123, 7, {{ round(0.06 + $intensity * 0.94, 2) }}); --i: {{ $loop->parent->index + $loop->index }}">{{ $count }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="dash-grid dash-grid-narrow">
                <section class="dash-card">
                    <header class="dash-card-head">
                        <div>
                            <h2>สินค้าที่ยังไม่มีคนดู</h2>
                            <p>ในช่วงที่เลือก ลองโปรโมตหรือปรับรูปให้น่าสนใจขึ้น</p>
                        </div>
                    </header>
                    @if ($unviewedProducts->isEmpty())
                        <p class="dash-empty">ทุกสินค้ามีคนเข้าดูแล้ว</p>
                    @else
                        <ul class="unviewed-list">
                            @foreach ($unviewedProducts as $product)
                                <li>
                                    <a href="{{ route('admin.products.edit', $product) }}">
                                        <img src="{{ $product->imageUrl() }}" alt="" loading="lazy">
                                        <span>
                                            {{ $product->name }}
                                            <small>{{ $product->category->name }}</small>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <section class="dash-card">
                    <header class="dash-card-head">
                        <div>
                            <h2>การเข้าชมล่าสุด</h2>
                            <p>6 คลิกล่าสุด</p>
                        </div>
                    </header>
                    <div class="views-table-wrap recent-table">
                        <table class="views-table">
                            <thead>
                                <tr>
                                    <th scope="col">เวลา</th>
                                    <th scope="col">สินค้า</th>
                                    <th scope="col">หมวด</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentViews as $view)
                                    <tr>
                                        <td>{{ $view->viewed_at->locale('th')->translatedFormat('j M H:i') }}</td>
                                        <td>{{ $view->product?->name ?? 'สินค้าที่ถูกลบ' }}</td>
                                        <td>{{ $view->product?->category->name ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        @endif
    </section>

    <script>
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.querySelectorAll('[data-count]').forEach((element, index) => {
                const target = Number(element.dataset.count);
                const decimals = Number(element.dataset.decimals ?? 0);
                const format = (value) => value.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                const duration = 900;
                let startedAt = null;

                element.textContent = format(0);

                const step = (timestamp) => {
                    startedAt ??= timestamp;
                    const progress = Math.min(1, (timestamp - startedAt) / duration);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    element.textContent = format(target * eased);

                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                };

                setTimeout(() => requestAnimationFrame(step), 120 + index * 80);
            });
        }
    </script>
@endsection
