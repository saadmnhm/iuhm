<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model
{
    use SoftDeletes;

    protected $table = 'newsletter_subscribers';

    protected $fillable = [
        'email',
        'is_active',
        'subscribed_at',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'subscribed_at' => 'datetime',
    ];
}
