<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['title', 'is_group'];

    protected $casts = [
        'is_group' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasMany
    {
        return $this->messages()->latest();
    }

    /**
     * Display title for a conversation from the perspective of $user.
     */
    public function titleFor(User $user): string
    {
        if ($this->is_group) {
            return $this->title ?? 'Group chat';
        }

        $other = $this->users->firstWhere('id', '!=', $user->id);

        return $other?->name ?? 'Conversation';
    }
}
