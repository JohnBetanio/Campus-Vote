<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    protected $fillable = ['title', 'status', 'winners'];

    /**
     * Cast winners JSON to array automatically.
     */
    protected $casts = [
        'winners' => 'array',
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function candidates()
    {
        return $this->hasManyThrough(Candidate::class, Position::class);
    }

    /**
     * Check if election is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if election has ended.
     */
    public function hasEnded(): bool
    {
        return $this->status === 'ended';
    }
}
