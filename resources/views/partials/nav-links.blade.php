<a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>หน้าแรก</a>
<a href="{{ route('shop.index') }}" @class(['is-active' => request()->routeIs('shop.*')])>สินค้า</a>
<a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>เกี่ยวกับเรา</a>
<a href="{{ route('articles.index') }}" @class(['is-active' => request()->routeIs('articles.*')])>ข่าวสาร</a>
<a href="{{ route('contact') }}" @class(['is-active' => request()->routeIs('contact')])>ติดต่อเรา</a>

@auth
    @if (Auth::user()->is_admin)
        <a href="{{ route('admin.dashboard') }}" class="nav-admin-link" @class(['is-active' => request()->routeIs('admin.*')])>หลังบ้านแอดมิน</a>
    @endif
    <form method="POST" action="{{ route('logout') }}" class="nav-logout-inline">
        @csrf
        <button type="submit" class="nav-logout-btn">ออกจากระบบ ({{ Str::limit(Auth::user()->name, 10) }})</button>
    </form>
@else
    <a href="{{ route('login') }}" @class(['is-active' => request()->routeIs('login')])>เข้าสู่ระบบ</a>
    <a href="{{ route('register') }}" @class(['is-active' => request()->routeIs('register'), 'nav-register-btn'])>สมัครสมาชิก</a>
@endauth
