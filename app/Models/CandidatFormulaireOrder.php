<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatFormulaireOrder extends Model
{
    protected $fillable = [
        'candidat_id',
        'programe_id',
        'formulaire_id',
        'order',
    ];
}
