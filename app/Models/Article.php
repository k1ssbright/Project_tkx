<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'excerpt', 'body', 'image', 'sort_order'])]
class Article extends Model
{
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
}
