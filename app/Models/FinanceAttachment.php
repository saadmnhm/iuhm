<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceAttachment extends Model
{
    use SoftDeletes;
    protected $table = 'finance_attachments';

    protected $fillable = [
        'transaction_id', 'file_path', 'file_name', 'file_type',
        'mime_type', 'file_size', 'notes',
    ];

    public function transaction()
    {
        return $this->belongsTo(FinanceTransaction::class, 'transaction_id');
    }
}
