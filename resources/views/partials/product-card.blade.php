<a class="product-card" href="{{ route('products.show', $product) }}">
    <div class="product-media">
        <img src="{{ asset($product->image) }}?v={{ filemtime(public_path($product->image)) }}" alt="{{ $product->name }}">
    </div>
    <div class="product-meta">
        <span>{{ $product->category->name }}</span>
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->formattedPrice() }}</p>
    </div>
</a>
