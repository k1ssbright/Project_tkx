<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Nano Circuit') — สนามโกคาร์ท Bangna-Trad KM.15</title>
    <meta name="description" content="Nano Circuit สนามโกคาร์ทบางนา–ตราด กม.15 เปิดทุกวัน 13:00–22:00">
    @include('partials.head-assets')
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#main">ข้ามไปที่เนื้อหา</a>

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

    <main id="main">
        @yield('content')
    </main>

    <footer class="site-footer" id="office">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="logo" href="{{ route('home') }}">
                    <img src="{{ asset('images/nanocircuit-logo.png') }}" alt="Nano Circuit">
                </a>
                <p>Nano Circuit Go-Kart Track, Bangna-Trad KM.15 — the newest go-kart racing venue on Bangkok’s eastern side, set to become the next hub for motorsport enthusiasts.</p>
                <a href="https://maps.app.goo.gl/srWzsP2ys1yA7cab7" target="_blank" rel="noopener">Bangna-trad, Bang Chalong, Bang Phli, Samut Prakan 10540</a>
                <a href="tel:0612324516">0612324516</a>
            </div>
            <div>
                <h2>Shop</h2>
                <a href="{{ route('shop.index') }}">สินค้าใหม่</a>
                <a href="{{ route('shop.index', ['category' => 'shirts']) }}">เสื้อ</a>
                <a href="{{ route('shop.index', ['category' => 'pants']) }}">กางเกง</a>
            </div>
            <div>
                <h2>Info</h2>
                <a href="{{ route('about') }}">เกี่ยวกับเรา</a>
                <a href="{{ route('news.index') }}">ข่าวสาร</a>
                <a href="{{ route('contact') }}">ติดต่อเรา</a>
                <a href="https://www.racefacer.com/en/login" target="_blank" rel="noopener">Time &amp; Membership</a>
            </div>
            <div>
                <h2>Account</h2>
                @auth
                    @can('access-admin')
                        <a href="{{ route('admin.dashboard') }}">หลังบ้าน</a>
                    @endcan
                    <form class="logout-form" method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">ออกจากระบบ</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
                    <a href="{{ route('register') }}">สมัครสมาชิก</a>
                @endauth
            </div>
        </div>
        <div class="footer-note">
            <span>Copyright © {{ now()->year }} Nano Circuit</span>
            <span>Monday–Sunday : 1pm to 10pm</span>
        </div>
    </footer>
    @include('partials.page-notice')
    <script>
        const menu = document.querySelector('details.menu');

        if (menu) {
            menu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    menu.open = false;
                });
            });

            document.addEventListener('click', (event) => {
                if (menu.open && ! menu.contains(event.target)) {
                    menu.open = false;
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    menu.open = false;
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
