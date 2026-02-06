<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Concerns\ManagesTableRows;
use App\Livewire\Concerns\HasValidationRules;
use App\Livewire\Concerns\HandlesFormPersistence;

#[Layout('layouts.app')]
class BusinessPlan extends Component
{
    use ManagesTableRows, HasValidationRules, WithFileUploads, HandlesFormPersistence;

    protected function getFormType(): string
    {
        return 'business_plan';
    }

    public function mount()
    {
        $this->mountFormPersistence();
    }

    protected function rules()
    {
        return match ($this->step) {
            1 => $this->step1Rules(),
            2 => $this->step2Rules(),
            3 => $this->step3Rules(),
            4 => $this->step4Rules(),
            5 => $this->step5Rules(),
            6 => $this->step6Rules(),
            7 => $this->step7Rules(),
            8 => $this->step8Rules(),
            default => [],
        };
    }

    public function render()
    {
        return view('livewire.front.business_plan.public-form-wizard');
    }
}
