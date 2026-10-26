@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <article class="article">
        <p class="breadcrumb">
            <a href="{{ route('home') }}">หน้าแรก</a>
            <span>/</span>
            <a href="{{ route('home') }}#articles">บทความ</a>
        </p>
        <p class="eyebrow">Article</p>
        <h1>{{ $article->title }}</h1>
        <img src="{{ $article->imageUrl() }}" alt="">
        @foreach (preg_split("/\n\s*\n/", trim($article->body)) as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach
    </article>
@endsection
