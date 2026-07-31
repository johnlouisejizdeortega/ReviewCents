<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoadmapStep extends Model
{
    protected $fillable = ['roadmap_id', 'resource_id', 'title', 'description', 'position'];

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(LessonCard::class)->orderBy('position');
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function lessonProgressFor(?User $user): ?LessonProgress
    {
        if (! $user) {
            return null;
        }

        return $this->lessonProgress()->where('user_id', $user->id)->first();
    }

    public function isCompletedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->progress()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->exists();
    }
}
