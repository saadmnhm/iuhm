<?php

namespace App\Livewire\Newsletter;

use App\Models\Newsletter;
use Livewire\Component;

class SubscribeForm extends Component
{
    public string $email = '';

    public string $name = '';

    public bool $success = false;

    public bool $stack = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:newsletter_subscribers,email',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'email.unique' => 'Cet email est déjà inscrit.',
        ];
    }

    public function subscribe(): void
    {
        $this->validate();

        Newsletter::updateOrCreate(
            ['email' => $this->email],
            [
                'name' => $this->name ?: null,
                'is_active' => true,
            ]
        );

        $this->success = true;
        $this->email = '';
        $this->name = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.site.newsletter.subscribe-form');
    }
}