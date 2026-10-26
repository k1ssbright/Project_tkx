@extends('layouts.admin')

@section('title', 'จัดการข่าวสาร')

@section('content')
    <section class="page-intro manage-intro">
        <div>
            <p class="eyebrow">News</p>
            <h1>จัดการข่าวสาร</h1>
            <p class="lede">เพิ่ม แก้ไข หรือลบข่าวสาร ใส่รายละเอียด รูปหน้าปก และรูปเพิ่มเติมได้</p>
        </div>
        <a class="text-link" href="{{ route('admin.news.create') }}">เพิ่มข่าวสาร</a>
    </section>

    @if ($articles->isEmpty())
        <p class="empty">ยังไม่มีข่าวสาร</p>
    @else
        <div class="manage-list">
            @foreach ($articles as $article)
                <article class="manage-item manage-item-wide">
                    <img src="{{ $article->imageUrl() }}" alt="">
                    <div>
                        <h2>{{ $article->title }}</h2>
                        <p>{{ $article->created_at?->locale('th')->translatedFormat('j M Y') }} · รูปเพิ่มเติม {{ $article->images_count }} รูป</p>
                    </div>
                    <div class="manage-actions">
                        <a href="{{ route('news.show', $article) }}" target="_blank" rel="noopener">ดูหน้าข่าว</a>
                        <a href="{{ route('admin.news.edit', $article) }}">แก้ไข</a>
                        <form method="post" action="{{ route('admin.news.destroy', $article) }}" onsubmit="return confirm('ลบข่าวนี้ใช่ไหม')">
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
