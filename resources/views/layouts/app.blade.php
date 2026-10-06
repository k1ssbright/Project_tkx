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
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <a class="logo" href="{{ route('home') }}">
                <img src="{{ asset('images/nanocircuit-logo.png') }}" alt="Nano Circuit">
            </a>
            <nav class="site-nav" aria-label="หลัก">
            <a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>หน้าแรก</a>
            <a href="{{ route('shop.index') }}" @class(['is-active' => request()->routeIs('shop.index') && ! request()->filled('category')])>สินค้าทั้งหมด</a>
            <a href="{{ route('shop.index', ['category' => 'shirts']) }}" @class(['is-active' => request('category') === 'shirts' || (request()->routeIs('products.show') && $product->category->slug === 'shirts')])>เสื้อ</a>
            <a href="{{ route('shop.index', ['category' => 'pants']) }}" @class(['is-active' => request('category') === 'pants' || (request()->routeIs('products.show') && $product->category->slug === 'pants')])>กางเกง</a>
            <a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>เกี่ยวกับเรา</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <div>
                <a class="logo" href="{{ route('home') }}">
                    <img src="{{ asset('images/nanocircuit-logo.png') }}" alt="Nano Circuit">
                </a>
                <p>เสื้อผ้าและกางเกงโทนขาว สำหรับคนที่ชอบลุคเรียบ</p>
            </div>
            <div class="footer-links">
                <a href="{{ route('shop.index', ['category' => 'shirts']) }}">เสื้อ</a>
                <a href="{{ route('shop.index', ['category' => 'pants']) }}">กางเกง</a>
                <a href="{{ route('about') }}">เกี่ยวกับเรา</a>
            </div>
            <p class="footer-note">เว็บนี้สำหรับโชว์สินค้า สอบถามไซส์และสต็อกได้ที่ร้าน</p>
        </div>
    </footer>
</body>
</html>
