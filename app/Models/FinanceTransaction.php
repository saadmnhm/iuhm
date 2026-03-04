<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceTransaction extends Model
{
    
    use SoftDeletes;

    protected $table = 'finance_transactions';

    protected $fillable = [
        'caisse_id', 'category_id', 'type', 'reference', 'label',
        'description', 'amount', 'date_transaction', 'beneficiaire',
        'mode_paiement', 'status', 'created_by', 'validated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date_transaction' => 'date',
    ];

    public function caisse()
    {
        return $this->belongsTo(FinanceCaisse::class, 'caisse_id');
    }

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function attachments()
    {
        return $this->hasMany(FinanceAttachment::class, 'transaction_id');
    }

    public function scopeRevenue($query)
    {
        return $query->where('type', 'revenue');
    }

    public function scopeDepense($query)
    {
        return $query->where('type', 'depense');
    }

    public function scopeValide($query)
    {
        return $query->where('status', 'valide');
    }

    public static function generateReference(string $type): string
    {
        $prefix = $type === 'revenue' ? 'REV' : 'DEP';
        $count = static::where('type', $type)->count() + 1;
        return $prefix . '-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
