<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title', 'หลังบ้าน') — NANO CIRCUIT Admin</title>
    @include('partials.head-assets')
    @stack('head')
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="logo" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/nanocircuit-logo.png') }}" alt="Nano Circuit">
            </a>
            <p class="admin-label">หลังบ้าน</p>
            <nav class="admin-nav" aria-label="เมนูหลังบ้าน">
                <a href="{{ route('admin.dashboard') }}" @class(['is-active' => request()->routeIs('admin.dashboard')])>ภาพรวมการเข้าชม</a>
                <a href="{{ route('admin.views') }}" @class(['is-active' => request()->routeIs('admin.views')])>ยอดคลิกรายสินค้า</a>
                <a href="{{ route('admin.products.index') }}" @class(['is-active' => request()->routeIs('admin.products.*')])>สินค้า</a>
                <a href="{{ route('admin.news.index') }}" @class(['is-active' => request()->routeIs('admin.news.*')])>ข่าวสาร</a>
            </nav>
            <div class="admin-user">
                <a href="{{ route('home') }}">ดูหน้าร้าน</a>
                <span>{{ auth()->user()->name }}</span>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">ออกจากระบบ</button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            @yield('content')
        </main>
    </div>
    @include('partials.page-notice')
    @stack('scripts')
</body>
</html>
