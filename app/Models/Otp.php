<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';
    protected $fillable = [
        'user_id',
        'email',
        'otp',
        'type',
        'attempts',
        'expires_at',
        'verified_at',
    ];


    //on peut $otp->expires_at->isPast() et laravl comprend qu'il sagit d'une date
    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];
}
