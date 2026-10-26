@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <article class="article">
        <p class="breadcrumb">
            <a href="{{ route('home') }}">หน้าแรก</a>
            <span>/</span>
            <a href="{{ route('news.index') }}">ข่าวสาร</a>
        </p>
        <p class="eyebrow">News · {{ $article->created_at?->locale('th')->translatedFormat('j F Y') }}</p>
        <h1>{{ $article->title }}</h1>
        <img src="{{ $article->imageUrl() }}" alt="">
        @foreach ($article->paragraphs() as $paragraph)
            <p>{!! nl2br(e($paragraph)) !!}</p>
        @endforeach

        @if ($article->images->isNotEmpty())
            <div class="article-gallery">
                @foreach ($article->images as $image)
                    <a href="{{ $image->imageUrl() }}" target="_blank" rel="noopener">
                        <img src="{{ $image->imageUrl() }}" alt="รูปประกอบข่าว {{ $loop->iteration }}" loading="lazy">
                    </a>
                @endforeach
            </div>
        @endif
    </article>

    @if ($others->isNotEmpty())
        <section class="section">
            <div class="section-heading">
                <p class="eyebrow">ข่าวสาร</p>
                <h2>ข่าวอื่น ๆ</h2>
            </div>
            <div class="article-grid news-grid">
                @foreach ($others as $other)
                    <article class="article-card">
                        <a href="{{ route('news.show', $other) }}">
                            <img src="{{ $other->imageUrl() }}" alt="">
                            <h3>{{ $other->title }}</h3>
                        </a>
                        <p>{{ $other->excerpt }}</p>
                        <a class="read-more" href="{{ route('news.show', $other) }}">อ่านต่อ</a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
