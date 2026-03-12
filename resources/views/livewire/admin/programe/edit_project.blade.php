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

            <!-- Icon Picker -->
            <div class="mt-6" x-data="{
                open: false,
                icons: [
                    'ri-file-list-3-line','ri-file-text-line','ri-survey-line','ri-clipboard-line',
                    'ri-book-open-line','ri-draft-line','ri-article-line','ri-newspaper-line',
                    'ri-task-line','ri-todo-line','ri-list-check-2','ri-list-ordered',
                    'ri-pie-chart-line','ri-bar-chart-line','ri-line-chart-line','ri-funds-line',
                    'ri-briefcase-line','ri-building-line','ri-store-line','ri-shopping-bag-line',
                    'ri-user-line','ri-team-line','ri-group-line','ri-contacts-line',
                    'ri-lightbulb-line','ri-brain-line','ri-graduation-cap-line','ri-medal-line',
                    'ri-heart-line','ri-shield-check-line','ri-settings-line','ri-tools-line',
                    'ri-money-dollar-circle-line','ri-bank-line','ri-wallet-line','ri-coin-line',
                    'ri-calendar-line','ri-time-line','ri-map-pin-line','ri-earth-line',
                    'ri-star-line','ri-trophy-line','ri-flag-line','ri-bookmark-line',
                    'ri-rocket-line','ri-map-line','ri-global-line','ri-compass-line',
                    'ri-leaf-line','ri-plant-line','ri-recycle-line','ri-sun-line',
                    'ri-home-line','ri-hospital-line','ri-government-line','ri-community-line'
                ]
            }">
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon *</label>

                <!-- Trigger button -->
                <button type="button" @click="open = true"
                    class="flex items-center gap-3 px-4 py-2.5 border border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition w-full text-left">
                    <span class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-lg shrink-0"
                          style="background-color: {{ $color }};">
                        <i class="{{ $icon }}"></i>
                    </span>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $icon }}</p>
                        <p class="text-xs text-gray-400">Click to change icon</p>
                    </div>
                    <i class="ri-arrow-down-s-line text-gray-400 ml-auto"></i>
                </button>
                @error('icon') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                <!-- Popup modal -->
                <div x-show="open" x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center"
                     style="background:rgba(0,0,0,0.45);">
                    <div @click.outside="open = false"
                         class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                                <i class="ri-remixicon-line text-blue-500"></i> Choose an Icon
                            </h3>
                            <button type="button" @click="open = false"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600">
                                <i class="ri-close-line text-lg"></i>
                            </button>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-7 gap-2">
                                <template x-for="ic in icons" :key="ic">
                                    <button type="button"
                                        @click="open = false; $wire.selectIcon(ic)"
                                        :class="'{{ $icon }}' === ic ? 'ring-2 ring-blue-500 text-white' : 'bg-gray-50 hover:bg-gray-100 text-gray-600'"
                                        :style="'{{ $icon }}' === ic ? 'background-color:{{ $color }};' : ''"
                                        class="w-10 h-10 rounded-lg flex items-center justify-center text-lg transition">
                                        <i :class="ic" style="pointer-events:none;"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
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

                <!-- Eligible Locations -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Eligible Locations (Multiple)
                    </label>
                    <button type="button"
                            wire:click="openLocationModal"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-left hover:border-blue-500 hover:bg-blue-50 transition">
                        <i class="ri-map-pin-line mr-1"></i>
                        Select locations from database
                    </button>

                    
                    @error('allowed_location_ids') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

@if($showLocationModal)
<div class="fixed inset-0 z-50  bg-black/60 p-4" x-data x-show="true" style="    background: #00000085;">
    <div class="flex min-h-full items-start justify-center py-6 md:py-10">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-hidden flex flex-col" @click.away="$wire.closeLocationModal()">

            <div class="px-6 py-4 border-b flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Select Eligible Locations</h3>
                    <p class="text-sm text-gray-500 mt-1">Use filters and search to manage large location lists easily. ({{ $locations->count() }}) result(s)</p>
                </div>
                <button type="button" wire:click="closeLocationModal" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <div class="px-6 py-4 border-b bg-gray-50 shrink-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <select wire:model.live="locationRegionFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none bg-white">
                        <option value="">All regions</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}">{{ $region }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="locationCityFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none bg-white">
                        <option value="">All cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>

                    <input type="text" wire:model.live.debounce.300ms="locationSearch" placeholder="Search region, city, prefecture..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:outline-none bg-white">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <div class="xl:col-span-2 min-h-0 flex flex-col border border-gray-200 rounded-2xl">
                    <div class="flex-1 overflow-y-auto min-h-80 max-h-[55vh] xl:max-h-full px-4 py-3">

                        {{-- Grid layout with 4 columns --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse($locations as $location)
                                <label class="flex items-start gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                    <input type="checkbox"
                                        wire:model.live="allowed_location_ids"
                                        value="{{ $location->id }}"
                                        class="mt-1 w-4 h-4 rounded text-blue-600 focus:ring-1 focus:ring-blue-500 shrink-0">
                                    <div class="min-w-0 text-sm text-gray-700">
                                        <div class="font-medium text-gray-800">{{ $location->prefecture }}</div>
                                        <div class="text-gray-500 text-xs mt-0.5 break-words">{{ $location->city }} · {{ $location->region }}</div>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-4 h-full flex items-center justify-center p-6 text-gray-500 text-center">
                                    No locations found.
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shrink-0">
                <span class="text-sm text-blue-700 font-medium">{{ count($allowed_location_ids ?? []) }} location(s) selected</span>
                <button type="button" wire:click="closeLocationModal" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition self-start sm:self-auto">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>
@endif

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
