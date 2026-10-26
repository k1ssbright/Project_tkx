@auth
    @can('access-admin')
        <a href="{{ route('admin.dashboard') }}">หลังบ้าน</a>
    @endcan
    <span class="account-name">{{ auth()->user()->name }}</span>
    <form class="logout-form" method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit">ออกจากระบบ</button>
    </form>
@else
    <a href="{{ route('login') }}" @class(['is-active' => request()->routeIs('login')])>เข้าสู่ระบบ</a>
    <a href="{{ route('register') }}" @class(['account-cta', 'is-active' => request()->routeIs('register')])>สมัครสมาชิก</a>
@endauth
