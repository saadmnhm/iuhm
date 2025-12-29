<div class="step-1">
    <h3 class="step-title">{{ __('messages.General_Informations') }}</h3>
    
    <div class="field-image text-center mb-6">
        <label for="profile-image" class="block mb-2 font-semibold">
            {{ __('messages.Photo_profil') }}
        </label>

        <div class="mb-3">
            <div
                class="mx-auto rounded-full border-4 border-dashed border-gray-300 bg-gray-100 flex items-center justify-center overflow-hidden"
                style="width: 200px; height: 200px;"
            >
                @if ($profile_image)
                    <img
                        src="{{ $profile_image->temporaryUrl() }}"
                        alt="Photo de profil"
                        class="w-full h-full object-cover"
                    >
                @else
                    <span class="text-gray-400">{{ __('messages.aucune_image') }}</span>
                @endif
            </div>
        </div>

        <input
            type="file"
            id="profile-image"
            wire:model="profile_image"
            accept="image/*"
            class="form-control"
        >

        @error('profile_image')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror

        <div wire:loading wire:target="profile_image" class="text-blue-500 text-sm mt-2">
            {{ __('messages.telechargement_cours') }}
        </div>
    </div>

    <div class="field-ceo_name mt-4 ">
        <label for="ceo_name" class="block disc mb-2 font-semibold">{{ __('messages.nom_pdg') }}</label>
        <input type="text" class="form-control border border-gray-300 rounded p-2 w-full" id="ceo_name" wire:model="ceo_name" >
        @error('ceo_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div class="field-age mb-2">
            <label for="age" class="block disc mb-2 font-semibold">{{ __('messages.age') }}</label>
            <input type="number" class="form-control border border-gray-300 rounded p-2 w-full" id="age" wire:model="age" min="18" max="100" placeholder="Ex: 25">
            @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="field-gender">
            <label for="gender" class="block disc mb-2 font-semibold">{{ __('messages.genre') }}</label>
            <select class="form-control border border-gray-300 rounded p-2 w-full" id="gender" wire:model="gender">
                <option value="">{{ __('messages.selectionner') }}</option>
                <option value="homme">{{ __('messages.homme') }}</option>
                <option value="femme">{{ __('messages.femme') }}</option>
            </select>
            @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="field-address mt-4">
        <label for="address" class="block disc mb-2 font-semibold">{{ __('messages.adresse') }}</label>
        <select class="form-control border border-gray-300 rounded p-2 w-full" id="address" wire:model="address_house">
            <option value="">{{ __('messages.selectionner_adresse') }}</option>
            <option value="Hay Mohamadi">{{ __('messages.hay_mohamadi') }}</option>
            <option value="Ain Sbaa">{{ __('messages.ain_sbaa') }}</option>
            <option value="Roches Noires">{{ __('messages.rochnoir') }}</option>
        </select>
        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="field-email mt-4">
        <label for="email" class="block disc mb-2 font-semibold">{{ __('messages.email') }}</label>
        <input type="email" class="form-control border border-gray-300 rounded p-2 w-full" id="email" wire:model="email" placeholder="exemple@email.com">
        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="field-phone mt-4">
        <label for="phone" class="block disc mb-2 font-semibold">{{ __('messages.telephone') }}</label>
        <input type="tel" class="form-control border border-gray-300 rounded p-2 w-full" id="phone" wire:model="phone" placeholder="0612345678">
        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>


    <div class="field-project-name mt-4">
        <label class=" disc mb-2" for="project-name">{{ __('messages.nom_de_projet') }}</label>
        <input  class="form-control" id="project-name" wire:model="project_name" >
        @error('project_name') <span>{{ $message }}</span> @enderror
    </div> 

    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="project-description">{{ __('messages.description_du_projet') }}</label>
        <textarea class="form-control" id="project-description" wire:model="description" ></textarea>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure mt-4">
        <label class=" disc mb-2" for="legal-structure">{{ __('messages.juridique_projet') }}</label>
        <input class="form-control" id="legal-structure" wire:model="legal_structure" >
        @error('legal_structure') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="resume">{{ __('messages.resume_executif') }}</label>
        <p class="instructions">{{ __('messages.instruction1') }}</p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9"></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>


</div>

