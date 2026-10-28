<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'excerpt', 'body', 'image', 'sort_order'])]
class Article extends Model
{
    public function images(): HasMany
    {
        return $this->hasMany(ArticleImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function imageUrl(): string
    {
        $absolute = public_path($this->image);
        $version = is_file($absolute) ? filemtime($absolute) : null;

        return asset($this->image).($version ? '?v='.$version : '');
    }

    /**
     * @return list<string>
     */
    public function paragraphs(): array
    {
        return array_values(array_filter(
            preg_split("/\n\s*\n/", trim(str_replace("\r\n", "\n", $this->body))),
            fn (string $paragraph) => trim($paragraph) !== '',
        ));
    }
}
