<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    protected $fillable = [
        'actor_type', 'actor_id', 'action',
        'target_type', 'target_id',
        'before', 'after', 'extra'
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
        'data' => 'array',
    ];
}

