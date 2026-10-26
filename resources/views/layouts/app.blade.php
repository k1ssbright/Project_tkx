<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NANO CIRCUIT') — เสื้อผ้าและกางเกงโทนขาว</title>
    <meta name="description" content="NANO CIRCUIT โชว์เสื้อผ้าและกางเกงโทนขาว ครีม และเบจ">
    @include('partials.head-assets')
</head>
<body>
    <header class="site-header">
        <div class="header-bar">
            <details class="menu">
                <summary aria-label="เปิดเมนู"><span></span><span></span><span></span></summary>
                <nav class="menu-panel" aria-label="เมนู">
                    @include('partials.nav-links')
                    <div class="menu-account">
                        @include('partials.account-links')
                    </div>
                </nav>
            </details>
            <a class="logo" href="{{ route('home') }}">
                <img src="{{ asset('images/nanocircuit-logo.png') }}" alt="Nano Circuit">
            </a>
            <nav class="site-nav" aria-label="หลัก">
                @include('partials.nav-links')
            </nav>
            <div class="site-account">
                @include('partials.account-links')
            </div>
        </div>
    </header>

    @if (session('status'))
        <p class="flash-status" role="status">{{ session('status') }}</p>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <a class="logo" href="{{ route('home') }}">
                    <img src="{{ asset('images/nanocircuit-logo.png') }}" alt="Nano Circuit">
                </a>
                <p>เสื้อผ้าและกางเกงโทนขาว สำหรับคนที่ชอบลุคเรียบ</p>
            </div>
            <div>
                <h2>ร้าน</h2>
                <a href="{{ route('shop.index') }}">สินค้าใหม่</a>
                <a href="{{ route('shop.index', ['category' => 'shirts']) }}">เสื้อ</a>
                <a href="{{ route('shop.index', ['category' => 'pants']) }}">กางเกง</a>
            </div>
            <div>
                <h2>ข้อมูล</h2>
                <a href="{{ route('about') }}">เกี่ยวกับเรา</a>
                <a href="{{ route('news.index') }}">ข่าวสาร</a>
            </div>
        </div>
        <p class="footer-note">© NANO CIRCUIT</p>
    </footer>
</body>
</html>
