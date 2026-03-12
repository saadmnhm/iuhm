<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoroccoLocation extends Model
{
    protected $fillable = [
        'region',
        'city',
        'prefecture',
    ];
}
