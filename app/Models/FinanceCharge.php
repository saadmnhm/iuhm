<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceCharge extends Model
{
    protected $table = 'finance_charges';

    protected $fillable = [
        'caisse_id', 'label', 'montant', 'frequence',
        'fournisseur', 'date_echeance', 'is_active', 'notes', 'created_by',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_echeance' => 'date',
        'is_active' => 'boolean',
    ];

    public function caisse()
    {
        return $this->belongsTo(FinanceCaisse::class, 'caisse_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
