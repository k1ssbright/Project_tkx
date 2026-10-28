<a class="product-card" href="{{ route('products.show', $product) }}">
    <div class="product-media">
        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy">
    </div>
    <div class="product-meta">
        <span>{{ $product->category->name }}</span>
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->formattedPrice() }}</p>
    </div>
</a>
