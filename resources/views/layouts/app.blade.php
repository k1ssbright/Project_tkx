<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TKX') — เสื้อผ้าและกางเกงโทนขาว</title>
    <meta name="description" content="TKX โชว์เสื้อผ้าและกางเกงโทนขาว ครีม และเบจ">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-256.png') }}?v={{ filemtime(public_path('images/favicon-256.png')) }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="header-bar">
            <details class="menu">
                <summary aria-label="เปิดเมนู"><span></span><span></span><span></span></summary>
                <nav class="menu-panel" aria-label="เมนู">
                    @include('partials.nav-links')
                </nav>
            </details>
            <a class="logo" href="{{ route('home') }}">
                <img src="{{ asset('images/nanocircuit-logo.png') }}" alt="Nano Circuit">
            </a>
            <nav class="site-nav" aria-label="หลัก">
                @include('partials.nav-links')
            </nav>
        </div>
    </header>

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
                <a href="{{ route('home') }}#articles">บทความ</a>
            </div>
        </div>
        <p class="footer-note">© TKX</p>
    </footer>
</body>
</html>
