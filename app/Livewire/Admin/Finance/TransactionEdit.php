<?php

namespace App\Livewire\Admin\Finance;

use App\Models\FinanceTransaction;
use App\Models\FinanceCategory;
use App\Models\FinanceAttachment;
use App\Models\AdminActivityLog;
use Livewire\Component;
use Livewire\WithFileUploads;

class TransactionEdit extends Component
{
    use WithFileUploads;

    public $transactionId;
    public $type, $category_id, $label, $description, $amount;
    public $date_transaction, $beneficiaire, $mode_paiement, $status;
    public $newAttachments = [];
    public $existingAttachments = [];

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
            'newAttachments.*' => 'nullable|file|max:10240',
        ];
    }

    public function mount($id)
    {
        $t = FinanceTransaction::with('attachments')->findOrFail($id);
        $this->transactionId = $t->id;
        $this->type = $t->type;
        $this->category_id = $t->category_id ?? '';
        $this->label = $t->label;
        $this->description = $t->description;
        $this->amount = $t->amount;
        $this->date_transaction = $t->date_transaction->format('Y-m-d');
        $this->beneficiaire = $t->beneficiaire;
        $this->mode_paiement = $t->mode_paiement;
        $this->status = $t->status;
        $this->existingAttachments = $t->attachments->toArray();
    }

    public function removeAttachment($attachmentId)
    {
        $att = FinanceAttachment::find($attachmentId);
        if ($att) {
            \Storage::disk('uploads')->delete($att->file_path);
            $att->delete();
            $this->existingAttachments = array_filter($this->existingAttachments, fn($a) => $a['id'] != $attachmentId);
        }
    }

    public function save()
    {
        $this->validate();

        $t = FinanceTransaction::findOrFail($this->transactionId);
        $t->update([
            'type' => $this->type,
            'category_id' => $this->category_id ?: null,
            'label' => $this->label,
            'description' => $this->description,
            'amount' => $this->amount,
            'date_transaction' => $this->date_transaction,
            'beneficiaire' => $this->beneficiaire,
            'mode_paiement' => $this->mode_paiement,
            'status' => $this->status,
        ]);

        if ($this->newAttachments) {
            foreach ($this->newAttachments as $file) {
                $path = $file->store('finance/transactions/' . $t->id, 'uploads');
                FinanceAttachment::create([
                    'transaction_id' => $t->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => 'piece_jointe',
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        AdminActivityLog::log('finance_transaction_updated', "Updated: {$t->label}", FinanceTransaction::class, $t->id);
        session()->flash('success', 'Transaction mise à jour!');
        return redirect()->route('admin.finance.index');
    }

    public function render()
    {
        $categories = FinanceCategory::where('type', $this->type)->orderBy('name')->get();

        return view('livewire.admin.finance.transaction-edit', compact('categories'))
            ->layout('layouts.admin', ['header' => 'Modifier Transaction']);
    }
}
