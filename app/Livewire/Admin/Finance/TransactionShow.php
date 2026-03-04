<?php

namespace App\Livewire\Admin\Finance;

use App\Models\FinanceTransaction;
use Livewire\Component;

class TransactionShow extends Component
{
    public $transactionId;
    public $transaction;

    public function mount($id)
    {
        $this->transactionId = $id;
        $this->transaction = FinanceTransaction::with(['category', 'creator', 'validator', 'attachments', 'caisse'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.finance.transaction-show')
            ->layout('layouts.admin', ['header' => 'Détail Transaction']);
    }
}
