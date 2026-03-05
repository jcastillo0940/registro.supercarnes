<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicVote extends Model
{
    protected $fillable = [
        'event_id',
        'participant_id',
        'voter_fingerprint',
        'ip_address',
        'user_agent',
        'scores',
    ];

    protected function casts(): array
    {
        return [
            'scores' => 'array',
        ];
    }
}
