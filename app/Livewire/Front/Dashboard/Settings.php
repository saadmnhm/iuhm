<?php

namespace App\Livewire\Front\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\MoroccoLocation;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Settings extends Component
{
    use WithFileUploads;
    
    public $candidat;
    
    // Profile Information
    public $nom;
    public $prenom;
    public $email;
    public $cin;
    public $date_naissance;
    public $age;
    public $niveau_etude;
    public $specialite;
    public $phone;
    public $gender;
    public $address;
    public $selected_region = '';
    public $selected_city = '';
    public $selected_prefecture = '';
    public $selected_location_id = null;
    public $address_detail = '';
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
        $this->cin = $this->candidat->cin;
        $this->date_naissance = $this->candidat->date_naissance ? $this->candidat->date_naissance->format('Y-m-d') : null;
        $this->age = $this->candidat->age;
        $this->niveau_etude = $this->candidat->niveau_etude;
        $this->specialite = $this->candidat->specialite;
        $this->phone = $this->candidat->phone;
        $this->gender = $this->candidat->gender;
        $this->address = $this->candidat->address;
        $this->selected_region = $this->candidat->selected_region;
        $this->selected_city = $this->candidat->selected_city;
        $this->selected_prefecture = $this->candidat->selected_prefecture;
        $this->selected_location_id = $this->candidat->morocco_location_id;
        $this->address_detail = $this->candidat->address_detail;

        $this->profile_image = $this->candidat->profile_image;
    }

    public function updateProfile()
    {
        $validated = $this->validate([
            'profile_image'       => 'nullable|string',
            'nom'                 => 'required|string|max:255',
            'prenom'              => 'required|string|max:255',
            'cin'                 => ['required', 'string', Rule::unique('candidat', 'cin')->ignore($this->candidat->id)],
            'email'               => ['required', 'email', 'max:255', Rule::unique('candidat', 'email')->ignore($this->candidat->id)],
            'date_naissance'      => 'required|date|before:today',
            'niveau_etude'        => 'required|string|max:255',
            'specialite'          => 'required|string|max:255',
            'phone'               => 'required|digits_between:8,20',
            'gender'              => 'required|string|in:homme,femme',
            'selected_region'     => 'required|string|max:255',
            'selected_city'       => 'required|string|max:255',
            'selected_prefecture' => 'required|string|max:255',
            'address_detail'      => 'required|string|max:500',
        ]);

        $age = Carbon::parse($validated['date_naissance'])->age;
        
        $locationId = null;
        if (
            !empty($validated['selected_region'])
            && !empty($validated['selected_city'])
            && !empty($validated['selected_prefecture'])
        ) {
            $location = MoroccoLocation::query()
                ->where('region', $validated['selected_region'])
                ->where('city', $validated['selected_city'])
                ->where('prefecture', $validated['selected_prefecture'])
                ->first();

            if (!$location) {
                $this->addError('selected_prefecture', 'Localisation invalide. Veuillez choisir une préfecture valide.');
                return;
            }

            $locationId = $location->id;
        }

        // Handle profile image upload
        if ($this->new_profile_image) {
            // Create directory if it doesn't exist
            $uploadPath = base_path('uploads/profile-images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Generate safe unique filename
            $ext      = strtolower($this->new_profile_image->getClientOriginalExtension());
            $baseName = pathinfo($this->new_profile_image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug(preg_replace('/[^a-zA-Z0-9\-_ ]/', '', $baseName)) ?: 'image';
            $filename = time() . '_' . uniqid() . '_' . $safeName . '.' . $ext;
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

        $validated['morocco_location_id'] = $locationId;
        $validated['address'] = $validated['selected_prefecture'];
        $validated['age'] = $age;

        $this->candidat->update($validated);
        $this->age = $age;
        
        // Refresh the profile image
        $this->profile_image = $this->candidat->fresh()->profile_image;

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

    public function updatedSelectedRegion()
    {
        $this->selected_city = '';
        $this->selected_prefecture = '';
        $this->selected_location_id = null;
    }

    public function updatedSelectedCity()
    {
        $this->selected_prefecture = '';
        $this->selected_location_id = null;
    }

    public function updatedSelectedPrefecture($value)
    {
        if (!$value || !$this->selected_region || !$this->selected_city) {
            $this->selected_location_id = null;
            return;
        }

        $this->selected_location_id = MoroccoLocation::query()
            ->where('region', $this->selected_region)
            ->where('city', $this->selected_city)
            ->where('prefecture', $value)
            ->value('id');
    }

    public function render()
    {
        $regions = MoroccoLocation::query()
            ->select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        $cities = collect();
        if (!empty($this->selected_region)) {
            $cities = MoroccoLocation::query()
                ->where('region', $this->selected_region)
                ->select('city')
                ->distinct()
                ->orderBy('city')
                ->pluck('city');
        }

        $prefectures = collect();
        if (!empty($this->selected_city)) {
            $prefectures = MoroccoLocation::query()
                ->where('region', $this->selected_region)
                ->where('city', $this->selected_city)
                ->select('prefecture')
                ->distinct()
                ->orderBy('prefecture')
                ->pluck('prefecture');
        }

        return view('livewire.front.dashboard.settings', [
            'regions' => $regions,
            'cities' => $cities,
            'prefectures' => $prefectures,
        ]);
    }
}