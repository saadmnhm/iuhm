<?php

namespace App\Livewire\Contact;

use App\Models\Contact;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $subject = '';

    public string $message = '';

    public bool $success = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'message.required' => 'Le message est obligatoire.',
        ];
    }

    public function send(): void
    {
        $this->validate();

        Contact::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'subject' => $this->subject ?: null,
            'message' => $this->message,
        ]);

        $this->success = true;
        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.site.contact.contact-form');
    }
}