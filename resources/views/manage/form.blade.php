@extends('layouts.app')

@section('title', $product->exists ? 'แก้ไข '.$product->name : 'เพิ่มสินค้า')

@section('content')
    @php
        $featured = session()->hasOldInput() ? (bool) old('is_featured') : (bool) $product->is_featured;
    @endphp

    <form class="editor" method="post" enctype="multipart/form-data" action="{{ $product->exists ? route('products.manage.update', $product) : route('products.manage.store') }}">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <a class="editor-back" href="{{ route('products.manage.index') }}">กลับไปรายการสินค้า</a>
        <header class="editor-head">
            <p class="eyebrow">{{ $product->exists ? 'แก้ไขสินค้า' : 'สินค้าใหม่' }}</p>
            <h1>{{ $product->exists ? $product->name : 'เพิ่มสินค้า' }}</h1>
        </header>

        <div class="editor-grid">
            <div class="editor-photo">
                <label class="photo-picker">
                    <img class="photo-preview" alt="" @if ($product->exists && $product->image) src="{{ $product->imageUrl() }}" @else hidden @endif>
                    <span class="photo-empty" @if ($product->exists && $product->image) hidden @endif>กดเพื่อเลือกรูป</span>
                    <span class="photo-change">{{ $product->exists ? 'เปลี่ยนรูป' : 'อัปโหลดรูป' }}</span>
                    <input class="photo-input" name="image" type="file" accept="image/jpeg,image/png,image/webp" @required(! $product->exists)>
                </label>
                <p class="photo-hint">รูปแนวตั้งจะพอดีกับกรอบสินค้า{{ $product->exists ? ' ถ้าไม่เลือกรูปใหม่ ระบบใช้รูปเดิม' : '' }}</p>
                @error('image')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="editor-fields">
                <section class="editor-section">
                    <h2>ข้อมูลสินค้า</h2>
                    <label class="field">
                        <span>ชื่อสินค้า</span>
                        <input name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')<p class="field-error">{{ $message }}</p>@enderror
                    </label>
                    <div class="field-row">
                        <label class="field">
                            <span>หมวดหมู่</span>
                            <select name="category_id" required>
                                <option value="">เลือกหมวดหมู่</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                        <label class="field">
                            <span>ราคา (บาท)</span>
                            <input name="price" type="number" min="0" step="1" value="{{ old('price', $product->price) }}" required>
                            @error('price')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                    </div>
                    <label class="field">
                        <span>ผ้า</span>
                        <input name="fabric" value="{{ old('fabric', $product->fabric) }}" required>
                        @error('fabric')<p class="field-error">{{ $message }}</p>@enderror
                    </label>
                </section>

                <section class="editor-section">
                    <h2>รายละเอียด</h2>
                    <label class="field">
                        <span>คำอธิบาย</span>
                        <textarea name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')<p class="field-error">{{ $message }}</p>@enderror
                    </label>
                    <div class="field-row">
                        <label class="field">
                            <span>สี</span>
                            <input name="colors" value="{{ old('colors', implode(', ', $product->colors ?? [])) }}" placeholder="ขาว, ครีม" required>
                            <small>คั่นแต่ละสีด้วยจุลภาค</small>
                            @error('colors')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                        <label class="field">
                            <span>ไซส์</span>
                            <input name="sizes" value="{{ old('sizes', implode(', ', $product->sizes ?? [])) }}" placeholder="S, M, L" required>
                            <small>คั่นแต่ละไซส์ด้วยจุลภาค</small>
                            @error('sizes')<p class="field-error">{{ $message }}</p>@enderror
                        </label>
                    </div>
                </section>

                <label class="feature-card">
                    <input name="is_featured" type="checkbox" value="1" @checked($featured)>
                    <span>
                        <strong>แสดงในสินค้าแนะนำ</strong>
                        สินค้านี้จะโชว์ในส่วนแนะนำบนหน้าแรก
                    </span>
                </label>

                <details class="slug-field" @if ($errors->has('slug')) open @endif>
                    <summary>ลิงก์สินค้า</summary>
                    <label class="field">
                        <input name="slug" value="{{ old('slug', $product->slug) }}" placeholder="เว้นว่างได้ ระบบจะตั้งให้">
                        <small>ใช้ต่อท้ายที่อยู่หน้าสินค้า เช่น /products/ivory-linen-shirt</small>
                        @error('slug')<p class="field-error">{{ $message }}</p>@enderror
                    </label>
                </details>

                <div class="editor-bar">
                    <a href="{{ route('products.manage.index') }}">ยกเลิก</a>
                    <button class="text-link" type="submit">{{ $product->exists ? 'บันทึก' : 'เพิ่มสินค้า' }}</button>
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
    </script>
@endsection
