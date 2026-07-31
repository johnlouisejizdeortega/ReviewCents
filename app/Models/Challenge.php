<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'description', 'difficulty', 'type', 'points',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ChallengeSubmission::class);
    }

    public function submissionFor(?User $user): ?ChallengeSubmission
    {
        if (! $user) {
            return null;
        }

        return $this->submissions()->where('user_id', $user->id)->latest()->first();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
