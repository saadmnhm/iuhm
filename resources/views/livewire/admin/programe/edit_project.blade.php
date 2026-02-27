<div class="p-8 bg-gray-50 min-h-screen">

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="bg-white rounded-xl shadow-sm p-8 ">

        <!-- SECTION 1: Project Settings -->
        <div class="mb-10">
            <h2 class="text-lg font-medium text-gray-800 mb-4">
                Project Settings
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Project Name *
                    </label>
                    <input type="text"
                           required
                           wire:model.live="project_name"
                           placeholder="e.g. Scholarship Application"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                @error('project_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>
                    <select wire:model="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description *
                </label>
                <textarea
                    required
                    rows="3"
                    placeholder="Explain the purpose of this form..."
                    wire:model.live="description"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- SECTION 2: Access Rules -->
        <div class="mb-10">
            <h2 class="text-lg font-medium text-gray-800 mb-4">
                User Access Rules
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Min Age -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Minimum Age *
                    </label>
                    <input type="number"
                           min="0"
                           placeholder="e.g. 18"
                           required
                           wire:model.live="min_age"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('min_age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Max Age -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Maximum Age *
                    </label>
                    <input type="number"
                           min="0"
                           placeholder="e.g. 35"
                           required
                           wire:model.live="max_age"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @error('max_age') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Address Source -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Address Source (Multiple)
                        </label>
                        <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto space-y-2">
                            @foreach($addresses as $address)
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" 
                                        wire:model.live="allowed_address_id"
                                        value="{{ $address->id }}"
                                        class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">
                                        {{ $address->address_line1 }} - {{ $address->city }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @if(is_array($allowed_address_id) && count($allowed_address_id) > 0)
                            <p class="text-xs text-blue-600 mt-1">{{ count($allowed_address_id) }} address(es) selected</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">No addresses selected (allows all)</p>
                        @endif
                        @error('allowed_address_id') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>
            </div>
        </div>

        <!-- SECTION 3: Form Fields (Champs) -->
        <div class="mb-10">
            <h2 class="text-lg font-medium text-gray-800 mb-4">
                Attached Formulaires
            </h2>

            <!-- Add Formulaire Button -->
            <button type="button"
                    wire:click="openFormulaireModal"
                    class="mb-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Attach Formulaire
            </button>

            <!-- Attached Formulaires List -->
            @if(count($attachedFormulaires) > 0)
                <div class="space-y-3">
                    @foreach($attachedFormulaires as $formulaire)
                        <div class="border border-gray-300 rounded-lg p-4 bg-white hover:shadow-md transition">
                            <div class="flex items-center justify-between gap-4">
                                <!-- Form Info -->
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">
                                        {{ $formulaire['title'] }}
                                        @if($formulaire['has_introduction'])
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">Has Introduction</span>
                                        @endif
                                    </h4>
                                    @if($formulaire['title_ar'])
                                        <p class="text-sm text-gray-500">{{ $formulaire['title_ar'] }}</p>
                                    @endif
                                </div>

                                <!-- Order Input -->
                                <div class="w-24">
                                    <label class="text-xs text-gray-600">Order</label>
                                    <input type="number"
                                           min="1"
                                           value="{{ $formulaire['order'] }}"
                                           wire:change="updateFormulaireOrder({{ $formulaire['id'] }}, $event.target.value)"
                                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                </div>

                                <!-- Status Select -->
                                <div class="w-32">
                                    <label class="text-xs text-gray-600">Status</label>
                                    <select wire:change="updateFormulaireStatus({{ $formulaire['id'] }}, $event.target.value)"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-sm ">
                                        <option value="active" {{ $formulaire['status'] == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $formulaire['status'] == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="draft" {{ $formulaire['status'] == 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>

                                <!-- Required Toggle -->
                                <div class="flex items-center gap-2">
                                    <label class="text-xs text-gray-600">Required</label>
                                    <input type="checkbox"
                                           {{ $formulaire['is_required'] ? 'checked' : '' }}
                                           wire:click="toggleFormulaireRequired({{ $formulaire['id'] }})"
                                           class="w-4 h-4 text-blue-600 border border-gray-300 rounded">
                                </div>

                                <!-- Delete Button -->
                                <button type="button"
                                        wire:click="detachFormulaire({{ $formulaire['id'] }})"
                                        onclick="return confirm('Are you sure you want to detach this formulaire?')"
                                        class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">No formulaires attached yet.</p>
                    <p class="text-xs text-gray-500">Click "Attach Formulaire" to add one.</p>
                </div>
            @endif

        </div>

        <!-- Formulaire Modal -->
        @if($showFormulaireModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                 x-data
                 x-show="true"
                 x-transition>
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative"
                     @click.away="$wire.closeFormulaireModal()">
                    
                    <!-- Close Button -->
                    <button wire:click="closeFormulaireModal"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                        ✕
                    </button>

                    <h2 class="text-lg font-semibold mb-4">Attach Formulaire</h2>

                    <!-- Formulaire Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Select Formulaire *
                        </label>
                        <select wire:model="selectedFormulaire"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">-- Choose a formulaire --</option>
                            @foreach($availableFormulaires as $form)
                                <option value="{{ $form['id'] }}">
                                    {{ $form['title'] }} @if($form['title_ar'])({{ $form['title_ar'] }})@endif
                                </option>
                            @endforeach
                        </select>
                        @error('selectedFormulaire') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Order *
                        </label>
                        <input type="number"
                               min="1"
                               wire:model="formulaireOrder"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('formulaireOrder') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status *
                        </label>
                        <select wire:model="formulaireStatus"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <!-- Required -->
                    <div class="mb-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   wire:model="formulaireRequired"
                                   class="w-4 h-4 text-blue-600 border border-gray-300 rounded">
                            <span class="text-sm text-gray-700">Required for submission</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2">
                        <button type="button"
                                wire:click="closeFormulaireModal"
                                class="px-4 py-2 bg-gray-200 border border-gray-300 rounded hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="button"
                                wire:click="attachFormulaire"
                                class="px-4 py-2 bg-blue-600 text-white border border-blue-700 rounded hover:bg-blue-700">
                            Attach
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <button type="button"
                    class="px-5 py-2.5 border rounded-lg text-gray-600 hover:bg-gray-100">
                Cancel
            </button>

            <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Save Project
            </button>
        </div>

    </form>
</div>
