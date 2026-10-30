<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['article_id', 'viewed_at'])]
class ArticleView extends Model
{
    public $timestamps = false;

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }
}
