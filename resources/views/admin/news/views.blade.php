@extends('layouts.admin')

@section('title', 'ยอดเข้าชมข่าวสาร')

@php
    $kpis = [
        ['label' => 'ยอดเข้าชมข่าวทั้งหมด', 'value' => number_format($views), 'delta' => $viewsDelta, 'note' => null],
        ['label' => 'ข่าวที่มีคนอ่าน', 'value' => number_format($readArticles).' / '.number_format($articles->count()), 'delta' => $readArticlesDelta, 'note' => null],
        ['label' => 'เฉลี่ยต่อวัน', 'value' => number_format($averagePerDay, 1), 'delta' => $averagePerDayDelta, 'note' => null],
        ['label' => 'ข่าวยอดนิยม', 'value' => $leader?->title ?? '-', 'delta' => null, 'note' => $leader ? number_format($leader->period_views_count).' ครั้ง · '.$leaderShare.'% ของทั้งหมด' : 'ยังไม่มีคนอ่านในช่วงนี้'],
    ];
@endphp

@section('content')
    <section class="dash">
        <header class="dash-head">
            <div>
                <p class="eyebrow">News analytics</p>
                <h1>ยอดเข้าชมข่าวสาร</h1>
                <p class="dash-sub">จำนวนครั้งที่มีคนเปิดอ่านข่าว ตั้งแต่ {{ $start->locale('th')->translatedFormat('j M Y') }} ถึงวันนี้ ไม่นับการเข้าดูของแอดมิน</p>
            </div>
            <nav class="dash-tabs" aria-label="เลือกช่วงเวลา">
                @foreach ($periods as $key => $option)
                    <a href="{{ route('admin.news-views', ['period' => $key]) }}" @class(['is-active' => $period === $key]) @if ($period === $key) aria-current="page" @endif>{{ $option['label'] }}</a>
                @endforeach
            </nav>
        </header>

        @if ($articles->isEmpty())
            <p class="empty">ยังไม่มีข่าวสาร</p>
        @else
            <div class="kpi-grid">
                @foreach ($kpis as $kpi)
                    <article class="kpi-card">
                        <span class="kpi-label">{{ $kpi['label'] }}</span>
                        <strong @class(['kpi-value', 'kpi-value-text' => $kpi['note'] !== null])>{{ $kpi['value'] }}</strong>
                        @if ($kpi['note'] !== null)
                            <span class="kpi-delta is-flat">{{ $kpi['note'] }}</span>
                        @elseif ($kpi['delta'] === null)
                            <span class="kpi-delta is-flat">ไม่มีข้อมูลช่วงก่อนหน้า</span>
                        @else
                            <span @class(['kpi-delta', 'is-up' => $kpi['delta'] > 0, 'is-down' => $kpi['delta'] < 0, 'is-flat' => $kpi['delta'] == 0])>
                                <b>{{ $kpi['delta'] > 0 ? '+' : '' }}{{ $kpi['delta'] }}%</b>
                                เทียบช่วงก่อนหน้า
                            </span>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="traffic-strip">
                <span>ช่วงก่อนหน้า <b>{{ number_format($previousViews) }}</b> ครั้ง</span>
                <span>ยังไม่มีคนอ่านในช่วงนี้ <b>{{ number_format($articles->count() - $readArticles) }}</b> ข่าว</span>
                <span>ยอดสะสมตั้งแต่เริ่มนับ <b>{{ number_format($allTimeViews) }}</b> ครั้ง</span>
                <a class="arrow-link" href="{{ route('admin.news.index') }}">จัดการข่าวสาร @include('partials.icon-arrow')</a>
            </div>

            <section class="dash-card news-trend-card">
                <header class="dash-card-head">
                    <div>
                        <h2>แนวโน้มการเข้าชม</h2>
                        <p>ยอดเข้าชมข่าวทุกข่าวรวมกัน{{ isset($periods[$period]['days']) ? 'รายวัน' : 'รายเดือน' }}</p>
                    </div>
                </header>
                @if ($views === 0)
                    <p class="dash-empty">ยังไม่มีคนอ่านข่าวในช่วงนี้</p>
                @else
                    <ol class="news-trend" aria-label="ยอดเข้าชมตามช่วงเวลา">
                        @foreach ($trend['buckets'] as $bucket)
                            <li title="{{ $bucket['label'] }}: {{ number_format($bucket['views']) }} ครั้ง">
                                <span class="news-trend-bar" style="--bar: {{ round($bucket['views'] / $trend['max'] * 100) }}%">
                                    <span class="sr-only">{{ $bucket['label'] }} {{ number_format($bucket['views']) }} ครั้ง</span>
                                </span>
                                <small @class(['is-hidden' => ! $bucket['showLabel']])>{{ $bucket['label'] }}</small>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </section>

            <div class="views-table-wrap news-views-table">
                <table class="views-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ข่าว</th>
                            <th scope="col">ลงเมื่อ</th>
                            <th scope="col" class="views-col">เข้าชม ({{ $periods[$period]['label'] }})</th>
                            <th scope="col">เทียบช่วงก่อน</th>
                            <th scope="col">ยอดสะสม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            @php
                                $articleDelta = $article->previous_views_count > 0
                                    ? round(($article->period_views_count - $article->previous_views_count) / $article->previous_views_count * 100, 1)
                                    : null;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a class="views-product" href="{{ route('news.show', $article) }}" target="_blank" rel="noopener">
                                        <img src="{{ $article->imageUrl() }}" alt="">
                                        <span>{{ $article->title }}</span>
                                    </a>
                                </td>
                                <td>{{ $article->created_at?->locale('th')->translatedFormat('j M Y') }}</td>
                                <td class="views-col">
                                    <div class="views-bar">
                                        <span style="width: {{ round($article->period_views_count / $maxViews * 100) }}%"></span>
                                    </div>
                                    <strong>{{ number_format($article->period_views_count) }}</strong>
                                </td>
                                <td>
                                    @if ($articleDelta === null)
                                        <span class="kpi-delta is-flat">{{ $article->period_views_count > 0 ? 'ใหม่' : '-' }}</span>
                                    @else
                                        <span @class(['kpi-delta', 'is-up' => $articleDelta > 0, 'is-down' => $articleDelta < 0, 'is-flat' => $articleDelta == 0])>
                                            <b>{{ $articleDelta > 0 ? '+' : '' }}{{ $articleDelta }}%</b>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ number_format($article->views_count) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
