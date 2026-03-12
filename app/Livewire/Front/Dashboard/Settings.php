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
        $this->age = $this->candidat->age;
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
            'new_profile_image' => [
                'nullable',
                'file',
                'max:2048', // 2 MB
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $value) {
                        return;
                    }

                    // ── Extension whitelist ─────────────────────────────────
                    $ext = strtolower($value->getClientOriginalExtension());
                    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                    if (! in_array($ext, $allowedExt, true)) {
                        $fail('Extension non autorisée. Formats acceptés : jpg, jpeg, png, webp.');
                        return;
                    }

                    // ── Real MIME-type (prevents disguised files) ───────────
                    $mime = $value->getMimeType();
                    $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
                    if (! in_array($mime, $allowedMime, true)) {
                        $fail('Le fichier doit être une image réelle (jpg, png, webp).');
                        return;
                    }

                    // ── Filename safety ─────────────────────────────────────
                    $baseName = pathinfo($value->getClientOriginalName(), PATHINFO_FILENAME);
                    if (preg_match('/[<>\/\\|?*:\x00-\x1f"\']/', $baseName)) {
                        $fail('Le nom du fichier contient des caractères non autorisés.');
                    }
                },
            ],
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('candidat', 'email')->ignore($this->candidat->id)],
            'age' => 'nullable|integer|min:18|max:100',
            'phone' => 'nullable|string|max:20',
            'gender'              => 'nullable|string|in:homme,femme',
            'selected_region'     => 'nullable|string|max:255|required_with:selected_city,selected_prefecture,address_detail',
            'selected_city'       => 'nullable|string|max:255|required_with:selected_region,selected_prefecture,address_detail',
            'selected_prefecture' => 'nullable|string|max:255|required_with:selected_region,selected_city,address_detail',
            'address_detail'      => 'nullable|string|max:500|required_with:selected_region,selected_city,selected_prefecture',
        ]);

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
        
        $validated['selected_region'] = $validated['selected_region'] ?? null;
        $validated['selected_city'] = $validated['selected_city'] ?? null;
        $validated['selected_prefecture'] = $validated['selected_prefecture'] ?? null;
        $validated['morocco_location_id'] = $locationId;
        $validated['address_detail'] = $validated['address_detail'] ?: null;

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