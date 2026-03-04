<?php

namespace App\Livewire\Admin\Finance;

use App\Models\FinanceCaisse;
use App\Models\FinanceTransaction;
use App\Models\FinanceCategory;
use App\Models\FinanceAttachment;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class TransactionCreate extends Component
{
    use WithFileUploads;

    public $type = 'depense';
    public $category_id = '';
    public $label = '';
    public $description = '';
    public $amount = '';
    public $date_transaction = '';
    public $beneficiaire = '';
    public $mode_paiement = 'espece';
    public $status = 'valide';
    public $attachments = [];

    protected function rules()
    {
        return [
            'type' => 'required|in:revenue,depense',
            'category_id' => 'nullable|exists:finance_categories,id',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'amount' => 'required|numeric|min:0.01',
            'date_transaction' => 'required|date',
            'beneficiaire' => 'nullable|string|max:255',
            'mode_paiement' => 'nullable|string|max:50',
            'status' => 'required|in:en_attente,valide,annule',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ];
    }

    public function mount()
    {
        $this->date_transaction = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        $caisse = FinanceCaisse::firstOrCreate(
            ['id' => 1],
            ['label' => 'Caisse Principale', 'solde_initial' => 0, 'created_by' => auth()->id()]
        );

        $transaction = FinanceTransaction::create([
            'caisse_id' => $caisse->id,
            'category_id' => $this->category_id ?: null,
            'type' => $this->type,
            'reference' => FinanceTransaction::generateReference($this->type),
            'label' => $this->label,
            'description' => $this->description,
            'amount' => $this->amount,
            'date_transaction' => $this->date_transaction,
            'beneficiaire' => $this->beneficiaire,
            'mode_paiement' => $this->mode_paiement,
            'status' => $this->status,
            'created_by' => auth()->id(),
        ]);

        // Save attachments
        if ($this->attachments) {
            foreach ($this->attachments as $file) {
                $path = $file->store('finance/transactions/' . $transaction->id, 'public');
                FinanceAttachment::create([
                    'transaction_id' => $transaction->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => 'piece_jointe',
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        AdminActivityLog::log('finance_transaction_created', "Created {$this->type}: {$this->label} ({$this->amount} MAD)", FinanceTransaction::class, $transaction->id);

        session()->flash('success', 'Transaction créée avec succès!');
        return redirect()->route('admin.finance.index');
    }

    public function render()
    {
        $categories = FinanceCategory::where('type', $this->type)->orderBy('name')->get();

        return view('livewire.admin.finance.transaction-create', compact('categories'))
            ->layout('layouts.admin', ['header' => 'Nouvelle Transaction']);
    }
}
