<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionVoter extends Model
{
    protected $fillable = [
        'voter_id',
        'election_id',
        'ip_address',
        'user_agent',
    ];

    public function voter(): BelongsTo
    {
        return $this->belongsTo(Voter::class);
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
}

