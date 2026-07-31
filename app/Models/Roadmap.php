<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Roadmap extends Model
{
    protected $fillable = ['category_id', 'title', 'slug', 'description', 'level', 'thumbnail'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RoadmapStep::class)->orderBy('position');
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    /**
     * Completion percentage for a given user across this roadmap's steps.
     */
    public function completionFor(?User $user): int
    {
        if (! $user) {
            return 0;
        }

        $total = $this->steps()->count();
        if ($total === 0) {
            return 0;
        }

        $done = Progress::where('user_id', $user->id)
            ->whereIn('roadmap_step_id', $this->steps()->pluck('id'))
            ->where('status', 'completed')
            ->count();

        return (int) round(($done / $total) * 100);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
