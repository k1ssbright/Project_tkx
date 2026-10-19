@extends('layouts.admin')

@section('title', 'จัดการข่าวสารและกิจกรรม')

@section('content')
    <div class="admin-container">
        <div class="admin-page-header">
            <div>
                <p class="eyebrow">News & Updates Management</p>
                <h1>จัดการหน้าข่าวสาร</h1>
                <p class="lede">เพิ่ม แก้ไข หรือลบข่าวสารประชาสัมพันธ์และกิจกรรม พร้อมรูปภาพและเนื้อหารายละเอียด</p>
            </div>
            <a href="{{ route('admin.articles.create') }}" class="btn-primary">＋ เพิ่มข่าวสารใหม่</a>
        </div>

        @if ($articles->isEmpty())
            <div class="admin-card text-center" style="padding: 48px 24px;">
                <p class="empty-text">ยังไม่มีข่าวสารในระบบ</p>
                <a href="{{ route('admin.articles.create') }}" class="btn-primary" style="margin-top: 16px;">เพิ่มข่าวสารเรื่องแรก</a>
            </div>
        @else
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 100px;">รูปภาพ</th>
                            <th>หัวข้อข่าวสาร / คำอธิบายย่อ</th>
                            <th style="width: 140px;">วันที่ลงข่าว</th>
                            <th class="text-right" style="width: 180px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td>
                                    <img src="{{ $article->imageUrl() }}" alt="{{ $article->title }}" class="table-img table-img-rect">
                                </td>
                                <td>
                                    <div class="table-article-title">
                                        <strong>{{ $article->title }}</strong>
                                    </div>
                                    <p class="table-article-excerpt">{{ Str::limit($article->excerpt, 120) }}</p>
                                </td>
                                <td>
                                    <span class="table-subtext">{{ $article->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="table-actions">
                                        <a href="{{ route('articles.show', $article) }}" target="_blank" class="btn-action-view" title="ดูหน้าร้าน">หน้าร้าน</a>
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn-action-edit">แก้ไข</a>
                                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" data-confirm="ต้องการลบข่าวสาร '{{ $article->title }}' ใช่หรือไม่?" data-confirm-button="ลบข่าวสาร">
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
