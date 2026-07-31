<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'image_path', 'live_url', 'repo_url', 'tags',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    public function tagList(): array
    {
        return $this->tags
            ? array_filter(array_map('trim', explode(',', $this->tags)))
            : [];
    }
}
