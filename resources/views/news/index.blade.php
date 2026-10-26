@extends('layouts.app')

@section('title', 'ข่าวสาร')

@section('content')
    <section class="page-intro">
        <p class="eyebrow">News</p>
        <h1>ข่าวสาร</h1>
        <p class="lede">อัปเดตคอลเลกชันใหม่ เคล็ดลับการแต่งตัวโทนขาว และข่าวจากร้าน NANO CIRCUIT</p>
    </section>

    @if ($articles->isEmpty())
        <p class="empty">ยังไม่มีข่าวสาร</p>
    @else
        <div class="article-grid news-grid">
            @foreach ($articles as $article)
                <article class="article-card">
                    <a href="{{ route('news.show', $article) }}">
                        <img src="{{ $article->imageUrl() }}" alt="">
                        <time datetime="{{ $article->created_at?->toDateString() }}">{{ $article->created_at?->locale('th')->translatedFormat('j F Y') }}</time>
                        <h3>{{ $article->title }}</h3>
                    </a>
                    <p>{{ $article->excerpt }}</p>
                    <a class="read-more" href="{{ route('news.show', $article) }}">อ่านต่อ</a>
                </article>
            @endforeach
        </div>

        @if ($articles->hasPages())
            <nav class="pager" aria-label="เปลี่ยนหน้า">
                @if ($articles->onFirstPage())
                    <span>ก่อนหน้า</span>
                @else
                    <a href="{{ $articles->previousPageUrl() }}">ก่อนหน้า</a>
                @endif
                <span>หน้า {{ $articles->currentPage() }} / {{ $articles->lastPage() }}</span>
                @if ($articles->hasMorePages())
                    <a href="{{ $articles->nextPageUrl() }}">ถัดไป</a>
                @else
                    <span>ถัดไป</span>
                @endif
            </nav>
        @endif
    @endif
@endsection
