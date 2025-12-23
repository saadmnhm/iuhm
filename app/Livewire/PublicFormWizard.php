<?php

namespace App\Livewire;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PublicFormWizard extends Component
{
    public $step = 1;

    public $name, $email, $phone, $address;
    public $city, $country, $notes;

    protected function rules()
    {
        return match ($this->step) {
            1 => ['name' => 'required'],
            2 => ['email' => 'required|email'],
            3 => ['phone' => 'required'],
            4 => ['address' => 'required'],
            5 => ['city' => 'required'],
            6 => ['country' => 'required'],
            7 => ['notes' => 'nullable'],
            default => [],
        };
    }

    public function next()
    {
        $this->validate();
        $this->step++;
    }

    public function back()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate();

        // Save later if you want (DB / email)
        session()->flash('success', 'Form submitted successfully!');

        $this->reset();
        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.public-form-wizard');
    }
}

