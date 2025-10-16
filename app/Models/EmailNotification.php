<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailNotification extends Model
{
    use HasFactory;

    protected $table = 'email_notifications';

    protected $fillable = [
        'id',
        'name',
        'description',
        'subject',
        'body',
        'created_at',
        'updated_at',
    ];

}
