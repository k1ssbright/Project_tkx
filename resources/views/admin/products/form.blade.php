@extends('layouts.admin')

@section('title', $product->exists ? 'แก้ไข '.$product->name : 'เพิ่มสินค้าใหม่')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@section('content')
    @php
        $featured = session()->hasOldInput() ? (bool) old('is_featured') : (bool) $product->is_featured;
    @endphp

    <div class="admin-container">
        <form class="editor" method="post" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
            @csrf
            @if ($product->exists)
                @method('PUT')
            @endif

            <a class="editor-back" href="{{ route('admin.products.index') }}">← กลับไปหน้ารายการสินค้า</a>
            <header class="editor-head">
                <p class="eyebrow">{{ $product->exists ? 'แก้ไขสินค้า' : 'เพิ่มสินค้าใหม่' }}</p>
                <h1>{{ $product->exists ? $product->name : 'เพิ่มสินค้าใหม่' }}</h1>
            </header>

            <div class="editor-grid">
                <div class="editor-photo">
                    <label class="photo-picker">
                        <img class="photo-preview" alt="" @if ($product->exists && $product->image) src="{{ $product->imageUrl() }}" @else hidden @endif>
                        <span class="photo-empty" @if ($product->exists && $product->image) hidden @endif>กดเพื่อเลือกรูปสินค้า</span>
                        <span class="photo-change">{{ $product->exists ? 'เปลี่ยนรูป' : 'อัปโหลดรูป' }}</span>
                        <input class="photo-input" name="image" type="file" accept="image/jpeg,image/png,image/webp" @required(! $product->exists)>
                    </label>
                    <p class="photo-hint">แสดงรูปต้นฉบับทั้งภาพ{{ $product->exists ? ' (หากไม่เลือกรูปใหม่ ระบบจะใช้รูปเดิม)' : '' }}</p>
                    @error('image')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div class="editor-fields">
                    <section class="editor-section">
                        <h2>ข้อมูลสินค้า</h2>
                        <label class="field">
                            <span>ชื่อสินค้า</span>
                            <input name="name" value="{{ old('name', $product->name) }}" required placeholder="เช่น เสื้อเชิ้ตลินิน">
                            @error('name')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                        <div class="field-row">
                            <label class="field">
                                <span>หมวดหมู่</span>
                                <select name="category_id" data-placeholder="เลือกหมวดหมู่" required>
                                    <option value="">เลือกหมวดหมู่</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="field-error">{{ $message }}</p>@enderror
                            </label>
                            <label class="field">
                                <span>ราคา (บาท)</span>
                                <input name="price" type="number" min="0" step="1" value="{{ old('price', $product->price) }}" required placeholder="เช่น 990">
                                @error('price')<p class="field-error">{{ $message }}</p>@enderror
                            </label>
                        </div>
                        <label class="field">
                            <span>ชนิดผ้า</span>
                            <input name="fabric" value="{{ old('fabric', $product->fabric) }}" required placeholder="เช่น ผ้าลินิน 100%">
                            @error('fabric')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                    </section>

                    <section class="editor-section">
                        <h2>รายละเอียด</h2>
                        <label class="field">
                            <span>คำอธิบายสินค้า</span>
                            <textarea name="description" rows="5" required placeholder="ใส่รายละเอียด รูปแบบการใช้งาน จุดเด่นของสินค้า...">{{ old('description', $product->description) }}</textarea>
                            @error('description')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                        <div class="field-row">
                            <label class="field">
                                <span>สี</span>
                                <input name="colors" value="{{ old('colors', implode(', ', $product->colors ?? [])) }}" placeholder="ขาว, ครีม" required>
                                <small>คั่นแต่ละสีด้วยเครื่องหมายจุลภาค (,)</small>
                                @error('colors')<p class="field-error">{{ $message }}</p>@enderror
                            </label>
                            <label class="field">
                                <span>ไซส์</span>
                                <input name="sizes" value="{{ old('sizes', implode(', ', $product->sizes ?? [])) }}" placeholder="S, M, L, XL" required>
                                <small>คั่นแต่ละไซส์ด้วยเครื่องหมายจุลภาค (,)</small>
                                @error('sizes')<p class="field-error">{{ $message }}</p>@enderror
                            </label>
                        </div>
                    </section>

                    <label class="feature-card">
                        <input name="is_featured" type="checkbox" value="1" @checked($featured)>
                        <span>
                            <strong>แสดงในสินค้าแนะนำ</strong>
                            สินค้านี้จะแสดงในส่วนสินค้าแนะนำบนหน้าแรกของเว็บไซต์
                        </span>
                    </label>

                    <details class="slug-field" @if ($errors->has('slug')) open @endif>
                        <summary>ตั้งค่าลิงก์ URL (Slug)</summary>
                        <label class="field">
                            <input name="slug" value="{{ old('slug', $product->slug) }}" placeholder="เว้นว่างได้ ระบบจะสร้างให้อัตโนมัติ">
                            <small>ใช้สำหรับต่อท้าย URL สินค้า เช่น /products/linen-shirt</small>
                            @error('slug')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                    </details>

                    <div class="editor-bar">
                        <a href="{{ route('admin.products.index') }}">ยกเลิก</a>
                        <button class="text-link" type="submit">{{ $product->exists ? 'บันทึกการแก้ไข' : 'บันทึกสินค้า' }}</button>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        window.jQuery('.editor select[name="category_id"]').select2({
            width: '100%',
            placeholder: 'เลือกหมวดหมู่',
            language: {
                noResults: () => 'ไม่พบรายการ',
            },
        });
    </script>
@endpush
