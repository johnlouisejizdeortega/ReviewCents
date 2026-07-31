<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    protected $fillable = [
        'category_id', 'submitted_by', 'title', 'slug', 'description',
        'url', 'type', 'thumbnail', 'avg_rating', 'reviews_count',
    ];

    protected $casts = [
        'avg_rating' => 'float',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function recalculateRating(): void
    {
        $this->avg_rating = round((float) $this->reviews()->avg('rating'), 2);
        $this->reviews_count = $this->reviews()->count();
        $this->save();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
