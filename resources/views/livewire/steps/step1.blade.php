<div class="step-1">
    <h3>General Informations</h3>
    
    <div class="field-image text-center mb-6">
        <label for="profile-image" class="block mb-2 font-semibold">Photo de profil *</label>
        @if ($profile_image)
            <div class="mb-3">
                <img src="{{ $profile_image->temporaryUrl() }}" class="mx-auto rounded-full border-4 border-gray-300" style="width: 200px; height: 200px; object-fit: cover;">
            </div>
        @else
            <div class="mb-3">
                <div class="mx-auto rounded-full border-4 border-dashed border-gray-300 bg-gray-100 flex items-center justify-center" style="width: 200px; height: 200px;">
                    <span class="text-gray-400">Aucune image</span>
                </div>
            </div>
        @endif
        <input type="file" id="profile-image" wire:model="profile_image" accept="image/*" class="form-control">
        @error('profile_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        <div wire:loading wire:target="profile_image" class="text-blue-500 text-sm mt-2">Téléchargement en cours...</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="field-age">
            <label for="age" class="block mb-2 font-semibold">Âge *</label>
            <input type="number" class="form-control border border-gray-300 rounded p-2 w-full" id="age" wire:model="age" min="18" max="100" placeholder="Ex: 25">
            @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="field-gender">
            <label for="gender" class="block mb-2 font-semibold">Genre *</label>
            <select class="form-control border border-gray-300 rounded p-2 w-full" id="gender" wire:model="gender">
                <option value="">Sélectionner...</option>
                <option value="homme">Homme</option>
                <option value="femme">Femme</option>
            </select>
            @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="field-address mt-4">
        <label for="address" class="block mb-2 font-semibold">Adresse *</label>
        <select class="form-control border border-gray-300 rounded p-2 w-full" id="address" wire:model="address_house">
            <option value="">Sélectionner une adresse...</option>
            <option value="hay_mohamadi">Hay Mohamadi</option>
            <option value="ain_sbaa">Ain Sbaa</option>
            <option value="rochnoir">Rochnoir</option>
        </select>
        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="field-email mt-4">
        <label for="email" class="block mb-2 font-semibold">Email *</label>
        <input type="email" class="form-control border border-gray-300 rounded p-2 w-full" id="email" wire:model="email" placeholder="exemple@email.com">
        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="field-phone mt-4">
        <label for="phone" class="block mb-2 font-semibold">Téléphone *</label>
        <input type="tel" class="form-control border border-gray-300 rounded p-2 w-full" id="phone" wire:model="phone" placeholder="0612345678">
        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>


    <div class="field-project-name">
        <label for="project-name">Nom de projet</label>
        <input  class="form-control" id="project-name" wire:model="project_name" >
        @error('project_name') <span>{{ $message }}</span> @enderror
    </div> 

    <div class="field-project-description">
        <label for="project-description">Description du projet</label>
        <textarea class="form-control" id="project-description" wire:model="description" ></textarea>
        @error('description') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure">
        <label for="legal-structure">Forme juridique du projet</label>
        <input class="form-control" id="legal-structure" wire:model="legal_structure" >
        @error('legal_structure') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif">
        <label for="resume">Résumé exécutif</label>
        <p>(Le résumé analytique donne un aperçu du contenu du plan d'affaires. Cette section devrait idéalement être rédigée après la finalisation du plan.)</p>
        <textarea class="form-control" id="resume" wire:model="resume_executif"  rows="9"></textarea>
        @error('resume_executif') <span>{{ $message }}</span> @enderror
    </div>


</div>

