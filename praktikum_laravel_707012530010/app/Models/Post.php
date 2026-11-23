<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'file_path', 'file_name'];

    protected $hidden = ['file_path'];
    protected $appends = ['file_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::get(
            fn(): ?string => $this->file_path
                ? url("/api/posts/{$this->id}/file")
                : null
        );
    }
}
