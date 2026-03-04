<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceCaisse extends Model
{
    use SoftDeletes;
    protected $table = 'finance_caisse';

    protected $fillable = ['label', 'solde_initial', 'description', 'created_by'];

    protected $casts = [
        'solde_initial' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'caisse_id');
    }

    public function charges()
    {
        return $this->hasMany(FinanceCharge::class, 'caisse_id');
    }

    /**
     * Solde actuel = solde_initial + revenues - dépenses
     */
    public function getSoldeActuelAttribute(): float
    {
        $revenues = $this->transactions()
            ->where('type', 'revenue')
            ->where('status', 'valide')
            ->sum('amount');
        $depenses = $this->transactions()
            ->where('type', 'depense')
            ->where('status', 'valide')
            ->sum('amount');

        return (float) $this->solde_initial + (float) $revenues - (float) $depenses;
    }
}
