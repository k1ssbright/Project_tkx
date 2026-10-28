<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['image', 'sort_order'])]
class ArticleImage extends Model
{
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function imageUrl(): string
    {
        $absolute = public_path($this->image);
        $version = is_file($absolute) ? filemtime($absolute) : null;

        return asset($this->image).($version ? '?v='.$version : '');
    }
}
