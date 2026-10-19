<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ระบบหลังบ้านแอดมิน') — TKX Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}" sizes="32x32">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}?v={{ filemtime(public_path('css/catalog.css')) }}">
    @stack('head')
</head>
<body class="admin-body">
    <header class="admin-topbar">
        <div class="admin-bar-inner">
            <div class="admin-brand">
                <a class="logo" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/nanocircuit-logo.png') }}" alt="Nano Circuit">
                </a>
                <span class="admin-badge">ADMIN PANEL</span>
            </div>

            <nav class="admin-nav" aria-label="เมนูผู้ดูแลระบบ">
                <a href="{{ route('admin.dashboard') }}" @class(['is-active' => request()->routeIs('admin.dashboard')])>
                    📊 ภาพรวมสถิติ
                </a>
                <a href="{{ route('admin.products.index') }}" @class(['is-active' => request()->routeIs('admin.products.*') || request()->routeIs('products.manage.*')])>
                    📦 จัดการสินค้า
                </a>
                <a href="{{ route('admin.articles.index') }}" @class(['is-active' => request()->routeIs('admin.articles.*')])>
                    📰 จัดการข่าวสาร
                </a>
            </nav>

            <div class="admin-user-menu">
                <a href="{{ route('home') }}" target="_blank" class="admin-storefront-btn">
                    🌐 ดูหน้าร้าน
                </a>
                <div class="admin-user-pill">
                    <span class="admin-user-avatar">👤</span>
                    <span class="admin-user-name">{{ Auth::user()?->name ?? 'Admin' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="admin-logout-form">
                    @csrf
                    <button type="submit" class="admin-logout-btn" title="ออกจากระบบ">
                        🚪 ออกจากระบบ
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="admin-main">
        @yield('content')
    </main>

    <footer class="admin-footer">
        <p>TKX / Nano Circuit — ระบบหลังบ้านสำหรับผู้ดูแลระบบ</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const pageNotice = {
            status: @json(session('status'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
            error: @json(session('error'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
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

        if (pageNotice.error) {
            Swal.fire({
                ...swalBase,
                icon: 'error',
                title: pageNotice.error,
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
                title: 'กรอกข้อมูลไม่ครบถ้วน',
                html: list,
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
