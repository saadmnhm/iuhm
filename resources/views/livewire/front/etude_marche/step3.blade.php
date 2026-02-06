<div class="step-3">
    <div class="complete-form text-center">
        <p style="text-align: center; font-weight: bold;">La Concurrence</p>
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2" for="nombre_concurrents_directs">11. Combien y a-t-il de concurrents directs ?</label>
        <textarea class="form-control" id="nombre_concurrents_directs" wire:model="nombre_concurrents_directs" @if($isReadOnly) readonly @endif></textarea>
        @error('nombre_concurrents_directs') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2" for="concurrents_indirects">12. Existe-t-il des concurrents indirects ? Quel est leur impact ?</label>
        <textarea class="form-control" id="concurrents_indirects" wire:model="concurrents_indirects" @if($isReadOnly) readonly @endif></textarea>
        @error('concurrents_indirects') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field-legal-structure mt-4">
        <label class="disc mb-2" for="taille_concurrents">13. Quelle est la taille des principaux concurrents ?</label>
        <textarea class="form-control" id="taille_concurrents" wire:model="taille_concurrents" @if($isReadOnly) readonly @endif></textarea>
        @error('taille_concurrents') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="informations_concurrents">14. Disposez-vous d'informations clés sur 2 ou 3 concurrents (chiffre d'affaires, politique commerciale, ancienneté…) ?</label>
        <textarea class="form-control" id="informations_concurrents" wire:model="informations_concurrents"  @if($isReadOnly) readonly @endif></textarea>
        @error('informations_concurrents') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="communication_concurrents">15. Comment vos concurrents communiquent-ils sur leurs produits ou services (publicité, réseaux sociaux, événements, etc.) ?</label>
        <textarea class="form-control" id="communication_concurrents" wire:model="communication_concurrents"  @if($isReadOnly) readonly @endif></textarea>
        @error('communication_concurrents') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>

