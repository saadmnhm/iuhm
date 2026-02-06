<div class="step-1">
    <h3 class="step-title"></h3>
    
<div class="complete-form text-center">
    <p style="text-align: center; font-weight: bold;">Service / Produit</p>
</div>
    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="produit-service">1. Quel est le produit ou service que vous allez vendre</label>
        <textarea class="form-control" id="produit-service" wire:model="produit_service" @if($isReadOnly) readonly @endif></textarea>
        @error('produit_service') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-project-description mt-4">
        <label class=" disc mb-2" for="description-offre">2. Vous pouvez décrire votre offre ?</label>
        <textarea class="form-control" id="description-offre" wire:model="description_offre" @if($isReadOnly) readonly @endif></textarea>
        @error('description_offre') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-legal-structure mt-4">
        <label class=" disc mb-2" for="benefices-clients">3. Quels bénéfices apporte-t-elle aux clients ? (qualité, gain de temps…)</label>
        <textarea class="form-control" id="benefices-clients" wire:model="benefices_clients" @if($isReadOnly) readonly @endif></textarea>
        @error('benefices_clients') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="prix-marche">4. Quel est le prix de votre produit ou service dans le marché ?</label>
        <p class="instructions"></p>
        <textarea class="form-control" id="prix-marche" wire:model="prix_marche"  @if($isReadOnly) readonly @endif></textarea>
        @error('prix_marche') <span>{{ $message }}</span> @enderror
    </div>
    <div class="field-Resume-executif mt-4">
        <label class=" disc mb-2 " for="controle-prix">5.Le prix peut-il être contrôlé ou dépend-il de l'environnement ?</label>
        <p class="instructions"></p>
        <textarea class="form-control" id="controle-prix" wire:model="controle_prix"  @if($isReadOnly) readonly @endif></textarea>
        @error('controle_prix') <span>{{ $message }}</span> @enderror
    </div>


 






</div>

