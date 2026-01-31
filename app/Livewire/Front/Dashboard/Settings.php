<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class Settings extends Component
{
    use WithFileUploads;
    
    public $candidat;
    
    // Profile Information
    public $nom;
    public $prenom;
    public $email;
    public $age;
    public $phone;
    public $gender;
    public $address;
    public $profile_image;
    public $new_profile_image;
    
    // Password Change
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    
    public $activeTab = 'profile';

    public function mount()
    {
        $this->candidat = Auth::guard('candidat')->user();
        
        $this->nom = $this->candidat->nom;
        $this->prenom = $this->candidat->prenom;
        $this->email = $this->candidat->email;
        $this->age = $this->candidat->age;
        $this->phone = $this->candidat->phone;
        $this->gender = $this->candidat->gender;
        $this->address = $this->candidat->address;
        $this->profile_image = $this->candidat->profile_image;
    }

    public function updateProfile()
    {
        $validated = $this->validate([
            'new_profile_image' => 'nullable|image|max:2048',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('candidat', 'email')->ignore($this->candidat->id)],
            'age' => 'nullable|integer|min:18|max:100',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:homme,femme',
            'address' => 'nullable|string|max:255',
        ]);

        // Handle profile image upload
        if ($this->new_profile_image) {
            // Create directory if it doesn't exist
            $uploadPath = base_path('uploads/profile-images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $this->new_profile_image->getClientOriginalExtension();
            $relativePath = 'profile-images/' . $filename;
            
            // Move the file to uploads directory
            $this->new_profile_image->storeAs('profile-images', $filename, 'uploads');
            
            // Delete old image if exists
            if ($this->candidat->profile_image) {
                $oldImagePath = base_path('uploads/' . $this->candidat->profile_image);
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }
            
            $validated['profile_image'] = $relativePath;
        }

        unset($validated['new_profile_image']);
        
        $this->candidat->update($validated);
        
        // Refresh the profile image
        $this->profile_image = $this->candidat->fresh()->profile_image;
        $this->new_profile_image = null;

        session()->flash('profile_success', 'Profile updated successfully!');
        
        $this->dispatch('profile-updated');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($this->current_password, $this->candidat->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        // Update password
        $this->candidat->update([
            'password' => Hash::make($this->new_password)
        ]);

        // Clear password fields
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        session()->flash('password_success', 'Password updated successfully!');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.front.dashboard.settings');
    }
}