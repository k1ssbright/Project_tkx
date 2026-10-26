@extends('layouts.app')

@section('title', 'สมัครสมาชิก')

@section('content')
    <section class="auth-card">
        <p class="eyebrow">Create account</p>
        <h1>สมัครสมาชิก</h1>

        <form class="auth-form" method="post" action="{{ route('register') }}">
            @csrf
            <label class="field">
                <span>ชื่อ</span>
                <input name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>
                @error('name')<p class="field-error">{{ $message }}</p>@enderror
            </label>
            <label class="field">
                <span>อีเมล</span>
                <input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </label>
            <label class="field">
                <span>รหัสผ่าน</span>
                <input name="password" type="password" autocomplete="new-password" minlength="8" required>
                <small>อย่างน้อย 8 ตัวอักษร</small>
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
            </label>
            <label class="field">
                <span>ยืนยันรหัสผ่าน</span>
                <input name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
            </label>
            <button class="text-link" type="submit">สมัครสมาชิก</button>
        </form>

        <p class="auth-switch">มีบัญชีแล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ</a></p>
    </section>
@endsection
