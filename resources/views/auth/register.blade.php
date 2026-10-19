@extends('layouts.app')

@section('title', 'สมัครสมาชิก')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <p class="eyebrow">Create Account</p>
                <h1>สมัครสมาชิก</h1>
                <p class="auth-subtitle">สมัครสมาชิกเพื่อเข้าใช้งานระบบ</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <label class="field">
                    <span>ชื่อ - นามสกุล</span>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="ชื่อของคุณ" required autofocus autocomplete="name">
                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="field">
                    <span>อีเมล (Email)</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="yourname@example.com" required autocomplete="username">
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="field">
                    <span>รหัสผ่าน (Password)</span>
                    <input type="password" name="password" placeholder="อย่างน้อย 6 ตัวอักษร" required autocomplete="new-password">
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="field">
                    <span>ยืนยันรหัสผ่าน (Confirm Password)</span>
                    <input type="password" name="password_confirmation" placeholder="ยืนยันรหัสผ่านอีกครั้ง" required autocomplete="new-password">
                </label>

                <button type="submit" class="auth-btn">สมัครสมาชิก</button>

                <div class="auth-footer">
                    <span>มีบัญชีผู้ใช้แล้ว?</span>
                    <a href="{{ route('login') }}">เข้าสู่ระบบที่นี่</a>
                </div>
            </form>
        </div>
    </div>
@endsection
