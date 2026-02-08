<?php

namespace App\Livewire\Admin\Programe;

use App\Models\ProgrameList;
use App\Models\Address; 
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class ProgrameCreate extends Component
{
    public ?int $programeId = null;
    public $project_name = '';
    public $description = '';
    public $slug = '';
    public $icon = 'ri-file-list-3-line';
    public $color = '#2f5496';
    public $bg_color = '#ffffff';
    public $min_age = null;
    public $max_age = null;
    public $allowed_address_id = []; 
    public $form_attached_id = null;
    public $sort_order = 0;
    public $is_active = true;
    public $created_by = null;

    public function testLivewire()
    {
        \Log::info('TEST: Livewire is working!');
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Livewire is working!'
        ]);
    }

    protected function rules()
    {
        return [
            'project_name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'bg_color' => 'nullable|string|max:7',
            'min_age' => 'required|integer|min:0',
            'max_age' => 'required|integer|min:0|gte:min_age',
            'allowed_address_id' => 'nullable|array',
            'form_attached_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'created_by' => 'nullable|integer|exists:users,id',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->programeId = $id;
            $list = ProgrameList::findOrFail($id);
            
            $this->project_name = $list->project_name;
            $this->description = $list->description;
            $this->slug = $list->slug;
            $this->icon = $list->icon;
            $this->color = $list->color;
            $this->bg_color = $list->bg_color;
            $this->min_age = $list->min_age;
            $this->max_age = $list->max_age;
            
            // Decode JSON to array
            $this->allowed_address_id = json_decode($list->allowed_address_id, true) ?? [];
            
            $this->form_attached_id = $list->form_attached_id;
            $this->sort_order = $list->sort_order;
            $this->is_active = $list->is_active;
        }
    }

    public function saveProjectList()
    {
        $created_by = Auth::id();

        \Log::info('saveProjectList called');
        \Log::info('Data:', [
            'project_name' => $this->project_name,
            'description' => $this->description,
            'icon' => $this->icon,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'allowed_address_id' => $this->allowed_address_id,
            'created_by' => $this->created_by,
        ]);

        try {
            $this->validate();
            
            \Log::info('Validation passed');

            // Auto-generate slug from project name
            $this->slug = Str::slug($this->project_name);

            $data = [
                'project_name' => $this->project_name,
                'description' => $this->description,
                'slug' => $this->slug,
                'icon' => $this->icon ?? 'ri-file-list-3-line',
                'color' => $this->color ?? '#2f5496',
                'bg_color' => $this->bg_color ?? '#ffffff',
                'min_age' => $this->min_age,
                'max_age' => $this->max_age,
                'allowed_address_id' => json_encode($this->allowed_address_id ?? []),
                'form_attached_id' => $this->form_attached_id,
                'sort_order' => $this->sort_order ?? 0,
                'is_active' => $this->is_active,
                'created_by' => $created_by,
            ];

            \Log::info('Data to save:', $data);

            if ($this->programeId) {
                ProgrameList::findOrFail($this->programeId)->update($data);
                \Log::info('Project updated', ['id' => $this->programeId]);
                session()->flash('success', 'Project updated successfully!');
            } else {
                $project = ProgrameList::create($data);
                \Log::info('Project created', ['id' => $project->id]);
                session()->flash('success', 'Project created successfully!');
            }

            return redirect()->route('admin.programe_zettat');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Validation Error',
                'message' => implode(', ', array_map(fn($errors) => implode(', ', $errors), $e->errors()))
            ]);
            
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error saving project: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Error saving project: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        // Fetch all addresses from database
        $addresses = Address::all();
        
        return view('livewire.admin.programe.create_project', [
            'addresses' => $addresses
        ])
            ->layout('layouts.admin', [
                'header' => $this->programeId ? 'Edit Project' : 'Create New Project'
            ]);
    }
}