<a href="{{ route('shop.index') }}" @class(['is-active' => request()->routeIs('shop.index') && ! request()->filled('category')])>สินค้าใหม่</a>
<a href="{{ route('shop.index', ['category' => 'shirts']) }}" @class(['is-active' => request('category') === 'shirts' || (request()->routeIs('products.show') && isset($product) && $product->category->slug === 'shirts')])>เสื้อ</a>
<a href="{{ route('shop.index', ['category' => 'pants']) }}" @class(['is-active' => request('category') === 'pants' || (request()->routeIs('products.show') && isset($product) && $product->category->slug === 'pants')])>กางเกง</a>
<a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>เกี่ยวกับเรา</a>
<a href="{{ route('products.manage.index') }}" @class(['is-active' => request()->routeIs('products.manage.*')])>จัดการสินค้า</a>
