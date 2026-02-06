<div class="step-4">
    <div class="complete-form text-center">
        <p style="text-align: center; font-weight: bold;">Approvisionnement & Fournisseurs</p>
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2" for="nombre_fournisseurs">16. Combien de fournisseurs peuvent répondre à vos besoins ?</label>
        <textarea class="form-control" id="nombre_fournisseurs" wire:model="nombre_fournisseurs" @if($isReadOnly) readonly @endif></textarea>
        @error('nombre_fournisseurs') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2" for="origine_fournisseurs">17. Sont-ils nationaux ou internationaux ?</label>
        <textarea class="form-control" id="origine_fournisseurs" wire:model="origine_fournisseurs" @if($isReadOnly) readonly @endif></textarea>
        @error('origine_fournisseurs') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field-legal-structure mt-4">
        <label class="disc mb-2" for="prix_fournisseurs">18. Leurs prix sont-ils raisonnables ?</label>
        <textarea class="form-control" id="prix_fournisseurs" wire:model="prix_fournisseurs" @if($isReadOnly) readonly @endif></textarea>
        @error('prix_fournisseurs') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="delais_livraison">19. Les fournisseurs peuvent-ils assurer des délais de livraison rapides et fiables ?</label>
        <textarea class="form-control" id="delais_livraison" wire:model="delais_livraison"  @if($isReadOnly) readonly @endif></textarea>
        @error('delais_livraison') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="stabilite_marche">20. Le marché d'approvisionnement est-il stable ?</label>
        <textarea class="form-control" id="stabilite_marche" wire:model="stabilite_marche"  @if($isReadOnly) readonly @endif></textarea>
        @error('stabilite_marche') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
</div>

