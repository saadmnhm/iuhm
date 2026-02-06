<div class="step-1">
    <h3 class="step-title"></h3>
    
<div class="complete-form text-center">
    <p style="text-align: center; font-weight: bold;">Clientèle cible</p>
</div>
    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="type-clients">6. À quel type de clients s'adresse votre offre ? (entreprises, particuliers…)</label>
        <textarea class="form-control" id="type-clients" wire:model="type_clients" @if($isReadOnly) readonly @endif></textarea>
        @error('type_clients') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="caracteristiques-clientele">7. Quelles sont les caractéristiques de votre clientèle cible ? (âge, activité, catégorie sociale…)</label>
        <textarea class="form-control" id="caracteristiques-clientele" wire:model="caracteristiques_clientele" @if($isReadOnly) readonly @endif></textarea>
        @error('caracteristiques_clientele') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure mt-4">
        <label class=" disc mb-2" for="frequence-consommation">8. Quelle est la fréquence de consommation ? (occasionnelle ou régulière)</label>
        <textarea class="form-control" id="frequence-consommation" wire:model="frequence_consommation" @if($isReadOnly) readonly @endif></textarea>
        @error('frequence_consommation') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="localisation-clients">9. Où se trouvent vos clients ? (ville, national, international)</label>
        <p class="instructions"></p>
        <textarea class="form-control" id="localisation-clients" wire:model="localisation_clients"  @if($isReadOnly) readonly @endif></textarea>
        @error('localisation_clients') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="exigences-principales">10. Quelles sont leurs principales exigences ? (qualité, délai, prix…)</label>
        <p class="instructions"></p>
        <textarea class="form-control" id="exigences-principales" wire:model="exigences_principales"  @if($isReadOnly) readonly @endif></textarea>
        @error('exigences_principales') <span>{{ $message }}</span> @enderror
    </div>


 






</div>

