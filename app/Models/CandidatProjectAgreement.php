<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatProjectAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidat_id',
        'project_id',
        'agreed_at',
        'agreed_ip',
        'project_idea',
        'how_knew',
    ];

    protected $casts = [
        'agreed_at' => 'datetime',
    ];
}
