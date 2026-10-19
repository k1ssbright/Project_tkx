@extends('layouts.app')

@section('title', 'ข่าวสารและบทความ')

@section('content')
    <section class="page-intro">
        <div>
            <p class="eyebrow">News & Updates</p>
            <h1>ข่าวสารและกิจกรรม</h1>
            <p class="lede">ติดตามเรื่องราว ข่าวสาร ประชาสัมพันธ์ และกิจกรรมล่าสุดจาก Nano Circuit</p>
        </div>
    </section>

    <section class="section">
        @if ($articles->isEmpty())
            <p class="empty">ยังไม่มีข่าวสารในขณะนี้</p>
        @else
            <div class="article-grid">
                @foreach ($articles as $article)
                    <article class="article-card">
                        <a href="{{ route('articles.show', $article) }}">
                            <img src="{{ $article->imageUrl() }}" alt="{{ $article->title }}">
                            <h3>{{ $article->title }}</h3>
                        </a>
                        <p>{{ $article->excerpt }}</p>
                        <a class="read-more" href="{{ route('articles.show', $article) }}">อ่านต่อ</a>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
