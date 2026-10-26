@extends('layouts.admin')

@section('title', $article->exists ? 'แก้ไข '.$article->title : 'เพิ่มข่าวสาร')

@section('content')
    <form class="editor" method="post" enctype="multipart/form-data" action="{{ $article->exists ? route('admin.news.update', $article) : route('admin.news.store') }}">
        @csrf
        @if ($article->exists)
            @method('PUT')
        @endif

        <a class="editor-back" href="{{ route('admin.news.index') }}">กลับไปรายการข่าวสาร</a>
        <header class="editor-head">
            <p class="eyebrow">{{ $article->exists ? 'แก้ไขข่าวสาร' : 'ข่าวใหม่' }}</p>
            <h1>{{ $article->exists ? $article->title : 'เพิ่มข่าวสาร' }}</h1>
        </header>

        <div class="editor-grid">
            <div class="editor-photo">
                <label class="photo-picker photo-picker-wide">
                    <img class="photo-preview" alt="" @if ($article->exists && $article->image) src="{{ $article->imageUrl() }}" @else hidden @endif>
                    <span class="photo-empty" @if ($article->exists && $article->image) hidden @endif>กดเพื่อเลือกรูปหน้าปก</span>
                    <span class="photo-change">{{ $article->exists ? 'เปลี่ยนรูปหน้าปก' : 'อัปโหลดรูปหน้าปก' }}</span>
                    <input class="photo-input" name="image" type="file" accept="image/jpeg,image/png,image/webp" @required(! $article->exists)>
                </label>
                <p class="photo-hint">รูปแนวนอนจะพอดีกับหน้าข่าว{{ $article->exists ? ' ถ้าไม่เลือกรูปใหม่ ระบบใช้รูปเดิม' : '' }}</p>
                @error('image')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="editor-fields">
                <section class="editor-section">
                    <h2>ข้อมูลข่าว</h2>
                    <label class="field">
                        <span>หัวข้อข่าว</span>
                        <input name="title" value="{{ old('title', $article->title) }}" required>
                        @error('title')<p class="field-error">{{ $message }}</p>@enderror
                    </label>
                    <label class="field">
                        <span>ข้อความสรุป</span>
                        <textarea name="excerpt" rows="3" maxlength="500" required>{{ old('excerpt', $article->excerpt) }}</textarea>
                        <small>แสดงบนการ์ดข่าวในหน้าข่าวสาร ไม่เกิน 500 ตัวอักษร</small>
                        @error('excerpt')<p class="field-error">{{ $message }}</p>@enderror
                    </label>
                </section>

                <section class="editor-section">
                    <h2>รายละเอียด</h2>
                    <label class="field">
                        <span>เนื้อหาข่าว</span>
                        <textarea name="body" rows="12" required>{{ old('body', $article->body) }}</textarea>
                        <small>เว้นบรรทัดว่างหนึ่งบรรทัดเพื่อขึ้นย่อหน้าใหม่</small>
                        @error('body')<p class="field-error">{{ $message }}</p>@enderror
                    </label>
                </section>

                <section class="editor-section">
                    <h2>รูปภาพเพิ่มเติม</h2>
                    @if ($article->exists && $article->images->isNotEmpty())
                        <div class="gallery-manage">
                            @foreach ($article->images as $image)
                                <label class="gallery-manage-item">
                                    <img src="{{ $image->imageUrl() }}" alt="">
                                    <span>
                                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" @checked(in_array((string) $image->id, array_map('strval', old('remove_images', [])), true))>
                                        ลบรูปนี้
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    <label class="field">
                        <span>เพิ่มรูป</span>
                        <input class="gallery-input" name="gallery[]" type="file" accept="image/jpeg,image/png,image/webp" multiple>
                        <small>เลือกได้หลายรูปพร้อมกัน ครั้งละไม่เกิน 12 รูป รูปละไม่เกิน 5 MB</small>
                        @error('gallery')<p class="field-error">{{ $message }}</p>@enderror
                        @foreach ($errors->get('gallery.*') as $messages)
                            <p class="field-error">{{ $messages[0] }}</p>
                        @endforeach
                    </label>
                    <div class="gallery-preview" hidden></div>
                </section>

                <details class="slug-field" @if ($errors->has('slug')) open @endif>
                    <summary>ลิงก์ข่าว</summary>
                    <label class="field">
                        <input name="slug" value="{{ old('slug', $article->slug) }}" placeholder="เว้นว่างได้ ระบบจะตั้งให้">
                        <small>ใช้ต่อท้ายที่อยู่หน้าข่าว เช่น /news/new-collection</small>
                        @error('slug')<p class="field-error">{{ $message }}</p>@enderror
                    </label>
                </details>

                <div class="editor-bar">
                    <a href="{{ route('admin.news.index') }}">ยกเลิก</a>
                    <button class="text-link" type="submit">{{ $article->exists ? 'บันทึก' : 'เพิ่มข่าวสาร' }}</button>
                </div>
            </div>
        </div>
    </form>

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

        document.querySelector('.gallery-input')?.addEventListener('change', (event) => {
            const container = document.querySelector('.gallery-preview');
            if (!container) return;
            container.replaceChildren(...Array.from(event.target.files ?? []).map((file) => {
                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = '';
                return image;
            }));
            container.hidden = container.children.length === 0;
        });
    </script>
@endsection
