<a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>หน้าแรก</a>
<a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>เกี่ยวกับเรา</a>
<a href="{{ route('home') }}#articles">ข่าวสาร</a>
<a href="#office">ติดต่อเรา</a>
<a href="{{ route('products.manage.index') }}" @class(['is-active' => request()->routeIs('products.manage.*')])>จัดการสินค้า</a>
