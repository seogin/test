<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthLog extends Model
{
    protected $fillable = [
        'actor_type', 'actor_id', 'action',
        'ip', 'user_agent', 'extra', 'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function actorAdmin(){
        return $this->belongsTo(Admin::class, 'actor_id');
    }

}

