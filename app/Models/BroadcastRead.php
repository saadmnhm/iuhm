<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BroadcastRead extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'broadcast_id',
        'candidat_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}
