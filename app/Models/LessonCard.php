<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonCard extends Model
{
    protected $fillable = ['roadmap_step_id', 'front', 'back', 'hint', 'position'];

    public function step(): BelongsTo
    {
        return $this->belongsTo(RoadmapStep::class, 'roadmap_step_id');
    }
}
