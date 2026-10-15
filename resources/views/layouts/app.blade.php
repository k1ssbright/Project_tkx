<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Nano Circuit') — สนามโกคาร์ท Bangna-Trad KM.15</title>
    <meta name="description" content="Nano Circuit สนามโกคาร์ทบางนา–ตราด กม.15 เปิดทุกวัน 13:00–22:00">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-256.png') }}?v={{ filemtime(public_path('images/favicon-256.png')) }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}?v={{ filemtime(public_path('css/catalog.css')) }}">
    @stack('head')
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

    <footer class="site-footer" id="office">
        <div class="footer-grid">
            <div>
                <h2>About us</h2>
                <p>Nano Circuit Go-Kart Track, Bangna-Trad KM.15 — the newest go-kart racing venue on Bangkok’s eastern side, set to become the next hub for motorsport enthusiasts.</p>
                <p>Monday–Sunday : 1pm to 10pm</p>
            </div>
            <div>
                <h2>Our services</h2>
                <a href="{{ route('about') }}">เกี่ยวกับเรา</a>
                <a href="https://www.racefacer.com/en/login" target="_blank" rel="noopener">Time &amp; Membership</a>
            </div>
            <div>
                <h2>Office</h2>
                <a href="https://maps.app.goo.gl/srWzsP2ys1yA7cab7" target="_blank" rel="noopener">Bangna-trad, Bang Chalong,</a>
                <a href="https://maps.app.goo.gl/srWzsP2ys1yA7cab7" target="_blank" rel="noopener">Bang Phli, Samut Prakan 10540</a>
                <a href="tel:0612324516">0612324516</a>
            </div>
        </div>
        <p class="footer-note">Copyright {{ date('Y') }} © Nano Circuit</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const pageNotice = {
            status: @json(session('status'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
            errors: @json($errors->all(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
        };

        const swalBase = {
            confirmButtonColor: '#fe7b07',
            cancelButtonColor: '#808080',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก',
        };

        if (pageNotice.status) {
            Swal.fire({
                ...swalBase,
                icon: 'success',
                title: pageNotice.status,
            });
        }

        if (pageNotice.errors.length > 0) {
            const list = document.createElement('ul');
            list.className = 'swal-error-list';
            pageNotice.errors.forEach((message) => {
                const item = document.createElement('li');
                item.textContent = message;
                list.append(item);
            });

            Swal.fire({
                ...swalBase,
                icon: 'error',
                title: 'กรอกข้อมูลไม่ครบ',
                html: list,
            });
        }

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

        document.querySelectorAll('[data-confirm]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();

                Swal.fire({
                    ...swalBase,
                    icon: 'warning',
                    title: form.dataset.confirm,
                    showCancelButton: true,
                    confirmButtonText: form.dataset.confirmButton || 'ยืนยัน',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
