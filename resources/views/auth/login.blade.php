@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <p class="eyebrow">Welcome Back</p>
                <h1>เข้าสู่ระบบ</h1>
                <p class="auth-subtitle">เข้าสู่ระบบเพื่อจัดการข้อมูลร้านค้าและบริการ</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <label class="field">
                    <span>อีเมล (Email)</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus autocomplete="username">
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="field">
                    <span>รหัสผ่าน (Password)</span>
                    <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <div class="auth-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span>จดจำการเข้าสู่ระบบ</span>
                    </label>
                </div>

                <button type="submit" class="auth-btn">เข้าสู่ระบบ</button>

                <div class="auth-footer">
                    <span>ยังไม่มีบัญชีผู้ใช้?</span>
                    <a href="{{ route('register') }}">สมัครสมาชิกที่นี่</a>
                </div>
            </form>
        </div>
    </div>
@endsection
