<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'name',
    'slug',
    'price',
    'description',
    'fabric',
    'sizes',
    'colors',
    'image',
    'sort_order',
    'is_featured',
])]
class Product extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(ProductView::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sizes' => 'array',
            'colors' => 'array',
            'price' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function formattedPrice(): string
    {
        return number_format($this->price).' บาท';
    }

    public function hasImageFile(): bool
    {
        return filled($this->image) && is_file(public_path($this->image));
    }

    public function imageUrl(): string
    {
        if (! $this->hasImageFile()) {
            return asset('images/placeholder.svg');
        }

        return asset($this->image).'?v='.filemtime(public_path($this->image));
    }
}
