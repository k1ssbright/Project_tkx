@extends('layouts.admin')

@section('title', 'รูปหน้าเว็บ')

@php
    $shapeHints = [
        'hero' => 'แนวนอนกว้าง อย่างน้อย 1600 px',
        'logo' => 'png พื้นหลังโปร่งใส',
        'photo' => 'แนวนอน สัดส่วน 3:2',
    ];
@endphp

@section('content')
    <form class="editor site-images" method="post" enctype="multipart/form-data" action="{{ route('admin.site-images.update') }}">
        @csrf
        @method('PUT')

        <header class="editor-head site-images-head">
            <p class="eyebrow">Site images</p>
            <h1>รูปหน้าเว็บ</h1>
            <p class="lede">กดที่รูปเพื่อเลือกไฟล์ใหม่ เปลี่ยนได้หลายรูปแล้วกดบันทึกครั้งเดียว ไฟล์ jpg, png หรือ webp รูปละไม่เกิน 5 MB</p>
        </header>

        @error('images')<p class="field-error">{{ $message }}</p>@enderror

        @foreach ($groups as $group => $images)
            <section class="site-image-group">
                <header class="site-image-group-head">
                    <h2>{{ $group }}</h2>
                    <span>{{ $images->count() }} รูป</span>
                </header>
                <div class="site-image-grid">
                    @foreach ($images as $image)
                        <article class="site-image-card">
                            <label @class(['site-image-picker', 'site-image-'.$image['shape']])>
                                <img class="photo-preview" src="{{ $image['url'] }}" alt="">
                                <span class="site-image-change">เปลี่ยนรูป</span>
                                <input class="photo-input" name="images[{{ $image['key'] }}]" type="file" accept="image/jpeg,image/png,image/webp" aria-label="เปลี่ยน{{ $image['label'] }}">
                            </label>
                            <div class="site-image-meta">
                                <h3>{{ $image['label'] }}</h3>
                                <p class="site-image-usage">ใช้ที่: {{ $image['usage'] }}</p>
                                <p class="site-image-hint">{{ $shapeHints[$image['shape']] }}</p>
                                <div class="site-image-foot">
                                    <span @class(['site-image-badge', 'is-custom' => ! $image['isDefault']]) data-badge>{{ $image['isDefault'] ? 'รูปเริ่มต้น' : 'อัปโหลดแล้ว' }}</span>
                                    @unless ($image['isDefault'])
                                        <button class="site-image-reset" type="submit" form="reset-{{ $image['key'] }}">ใช้รูปเริ่มต้น</button>
                                    @endunless
                                </div>
                                @error('images.'.$image['key'])<p class="field-error">{{ $message }}</p>@enderror
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="editor-bar">
            <span class="site-image-count" hidden></span>
            <button class="text-link" type="submit">บันทึกรูป</button>
        </div>
    </form>

    @foreach ($groups->flatten(1)->reject(fn (array $image): bool => $image['isDefault']) as $image)
        <form id="reset-{{ $image['key'] }}" method="post" action="{{ route('admin.site-images.destroy', $image['key']) }}" data-confirm="กลับไปใช้รูปเริ่มต้นใช่ไหม" data-confirm-button="ใช้รูปเริ่มต้น">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <script>
        const pendingCount = document.querySelector('.site-image-count');

        document.querySelectorAll('.site-image-card').forEach((card) => {
            card.querySelector('.photo-input')?.addEventListener('change', (event) => {
                const file = event.target.files?.[0];
                if (!file) return;
                card.querySelector('.photo-preview').src = URL.createObjectURL(file);
                card.classList.add('is-pending');

                const badge = card.querySelector('[data-badge]');
                badge.textContent = 'ยังไม่ได้บันทึก';
                badge.classList.add('is-pending');

                const total = document.querySelectorAll('.site-image-card.is-pending').length;
                pendingCount.textContent = `เลือกรูปใหม่ ${total} รูป ยังไม่ได้บันทึก`;
                pendingCount.hidden = false;
            });
        });
    </script>
@endsection
