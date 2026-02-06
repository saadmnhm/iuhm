<div class="step-1">
    <h3 class="step-title"></h3>

    <div class="field-project-name mt-4">
        <label class="disc mb-2" for="idee-projet">1. L'idée de mon projet</label>
        <textarea class="form-control" id="idee-projet" wire:model="idee_projet" @if($isReadOnly) readonly @endif></textarea>
        @error('idee_projet') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-project-description mt-4">
        <label class="disc mb-2" for="resume-idee">2. Résumes ton idée en une phrase</label>
        <input class="form-control" id="resume-idee" wire:model="resume_idee" @if($isReadOnly) readonly @endif>
        @error('resume_idee') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-legal-structure mt-4">
        <label class="disc mb-2" for="besoin-projet">3. A quel besoin précis répond mon idée de projet ?</label>
        <textarea class="form-control" id="besoin-projet" wire:model="besoin_projet" @if($isReadOnly) readonly @endif></textarea>
        @error('besoin_projet') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="produits-services">4. Quels sont les produits ou services que vous proposez</label>
        <textarea class="form-control" id="produits-services" wire:model="produits_services" rows="5" @if($isReadOnly) readonly @endif></textarea>
        @error('produits_services') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="clients-identifies">5. Ai-je identifié qui pourraient être mes clients ? Qui sont-ils ?</label>
        <textarea class="form-control" id="clients-identifies" wire:model="clients_identifies" rows="5" @if($isReadOnly) readonly @endif></textarea>
        @error('clients_identifies') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="idee-existe-marche">6. Mon idée existe-t-elle déjà sur le marché ?</label>
        <textarea class="form-control" id="idee-existe-marche" wire:model="idee_existe_marche" rows="5" @if($isReadOnly) readonly @endif></textarea>
        @error('idee_existe_marche') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="valeur-ajoutee">7. Quelle est la valeur ajoutée de l'idée de Votre projet ?</label>
        <textarea class="form-control" id="valeur-ajoutee" wire:model="valeur_ajoutee" rows="5" @if($isReadOnly) readonly @endif></textarea>
        @error('valeur_ajoutee') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2" for="resultats-prevus">8. Quelles sont les résultats prévues</label>
        <textarea class="form-control" id="resultats-prevus" wire:model="resultats_prevus" rows="5" @if($isReadOnly) readonly @endif></textarea>
        @error('resultats_prevus') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2">9. Mes proches comprennent-ils mon idée quand je leur en parle ?</label>
        <div class="checktf">
            <label>
                <input type="radio" wire:model="proches_comprennent" value="oui" @if($isReadOnly) disabled @endif> OUI
            </label>
            <label>
                <input type="radio" wire:model="proches_comprennent" value="non" @if($isReadOnly) disabled @endif> NON
            </label>
        </div>
        @error('proches_comprennent') <span>{{ $message }}</span> @enderror
    </div>

    <div class="field-Resume-executif mt-4">
        <label class="disc mb-2">10. Leurs réactions et commentaires sont-ils positifs ?</label>
        <div class="checktf">
            <label>
                <input type="radio" wire:model="reactions_positives" value="oui" @if($isReadOnly) disabled @endif> OUI
            </label>
            <label>
                <input type="radio" wire:model="reactions_positives" value="non" @if($isReadOnly) disabled @endif> NON
            </label>
        </div>
        @error('reactions_positives') <span>{{ $message }}</span> @enderror
    </div>
</div>
