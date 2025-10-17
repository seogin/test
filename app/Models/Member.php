<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Member extends User
{
    use HasFactory, SoftDeletes;
    protected $table = 'members';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'city',
        'state',
        'country',
        'verified',
        'subscription',
        'profile_photo',
        'uploaded_files',
    ];

    protected $hidden = [
        'password'
    ];
}