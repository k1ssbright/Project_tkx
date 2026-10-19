@extends('layouts.admin')

@section('title', $article->exists ? 'แก้ไขข่าวสาร: '.$article->title : 'เพิ่มข่าวสารใหม่')

@section('content')
    <div class="admin-container">
        <form class="editor" method="post" enctype="multipart/form-data" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
            @csrf
            @if ($article->exists)
                @method('PUT')
            @endif

            <a class="editor-back" href="{{ route('admin.articles.index') }}">← กลับไปหน้ารายการข่าวสาร</a>
            <header class="editor-head">
                <p class="eyebrow">{{ $article->exists ? 'แก้ไขข่าวสาร' : 'เพิ่มข่าวสารใหม่' }}</p>
                <h1>{{ $article->exists ? $article->title : 'เพิ่มข่าวสารใหม่' }}</h1>
            </header>

            <div class="editor-grid">
                <!-- Image upload column -->
                <div class="editor-photo">
                    <label class="photo-picker photo-picker-rect">
                        <img class="photo-preview" alt="" @if ($article->exists && $article->image) src="{{ $article->imageUrl() }}" @else hidden @endif>
                        <span class="photo-empty" @if ($article->exists && $article->image) hidden @endif>กดเพื่อเลือกรูปภาพข่าวสาร</span>
                        <span class="photo-change">{{ $article->exists ? 'เปลี่ยนรูปภาพ' : 'อัปโหลดรูปภาพ' }}</span>
                        <input class="photo-input" name="image" type="file" accept="image/jpeg,image/png,image/webp" @required(! $article->exists)>
                    </label>
                    <p class="photo-hint">แนะนำรูปภาพแนวนอน (สัดส่วน 16:9){{ $article->exists ? ' (หากไม่เลือกรูปใหม่ ระบบจะใช้รูปเดิม)' : '' }}</p>
                    @error('image')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <!-- Form fields column -->
                <div class="editor-fields">
                    <section class="editor-section">
                        <h2>ข้อมูลข่าวสาร</h2>
                        <label class="field">
                            <span>หัวข้อข่าวสาร / กิจกรรม</span>
                            <input name="title" value="{{ old('title', $article->title) }}" required placeholder="เช่น เปิดให้บริการสนามโกคาร์ทช่วงวันหยุด">
                            @error('title')<p class="field-error">{{ $message }}</p>@enderror
                        </label>

                        <label class="field">
                            <span>คำอธิบายย่อ (สรุปข่าวสาร)</span>
                            <textarea name="excerpt" rows="3" required placeholder="สรุปเนื้อหาสั้นๆ สำหรับแสดงในการ์ดหน้าข่าวสาร...">{{ old('excerpt', $article->excerpt) }}</textarea>
                            <small>จะแสดงในการ์ดหน้ารวมข่าวสาร</small>
                            @error('excerpt')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                    </section>

                    <section class="editor-section">
                        <h2>เนื้อหาและรายละเอียดข่าวสาร</h2>
                        <label class="field">
                            <span>เนื้อหาข่าวสารฉบับเต็ม</span>
                            <textarea name="body" rows="10" required placeholder="ใส่รายละเอียดเนื้อหาข่าวสาร สามารถเคาะเว้นวรรคเพื่อแบ่งย่อหน้าได้...">{{ old('body', $article->body) }}</textarea>
                            <small>สามารถเคาะบรรทัดว่าง 1 บรรทัดเพื่อสร้างย่อหน้าใหม่</small>
                            @error('body')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                    </section>

                    <details class="slug-field" @if ($errors->has('slug')) open @endif>
                        <summary>ตั้งค่าลิงก์ URL (Slug)</summary>
                        <label class="field">
                            <input name="slug" value="{{ old('slug', $article->slug) }}" placeholder="เว้นว่างได้ ระบบจะสร้างให้อัตโนมัติ">
                            <small>ใช้สำหรับต่อท้าย URL หน้าข่าวสาร เช่น /articles/grand-opening</small>
                            @error('slug')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                    </details>

                    <div class="editor-bar">
                        <a href="{{ route('admin.articles.index') }}">ยกเลิก</a>
                        <button class="text-link" type="submit">{{ $article->exists ? 'บันทึกการแก้ไข' : 'บันทึกข่าวสาร' }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('.photo-input')?.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            const preview = document.querySelector('.photo-preview');
            const empty = document.querySelector('.photo-empty');
            if (!file || !preview) return;
            preview.src = URL.createObjectURL(file);
            preview.hidden = false;
            if (empty) empty.hidden = true;
        });
    </script>
@endsection
