@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ')

@section('content')
    <section class="auth-card">
        <p class="eyebrow">Sign in</p>
        <h1>เข้าสู่ระบบ</h1>

        <form class="auth-form" method="post" action="{{ route('login') }}">
            @csrf
            <label class="field">
                <span>อีเมล</span>
                <input name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </label>
            <label class="field">
                <span>รหัสผ่าน</span>
                <input name="password" type="password" autocomplete="current-password" required>
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
            </label>
            <label class="remember">
                <input name="remember" type="checkbox" value="1" @checked(old('remember'))>
                จดจำการเข้าสู่ระบบ
            </label>
            <button class="text-link" type="submit">เข้าสู่ระบบ</button>
        </form>

        <p class="auth-switch">ยังไม่มีบัญชี? <a href="{{ route('register') }}">สมัครสมาชิก</a></p>
    </section>
@endsection
