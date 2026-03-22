<div class="p-8 bg-gray-50 min-h-screen">



    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
            <p class="font-semibold">Please fix the following errors:</p>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="saveProjectList" class="bg-white rounded-xl shadow-sm p-8 ">

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
                           wire:model="project_name"
                           placeholder="e.g. Scholarship Application"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('project_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" wire:model="is_active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Logos Section -->
            <div class="mt-6 mb-6">
                <h3 class="text-md font-medium text-gray-800 mb-3">Project Logos</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo 1 (Required) *</label>
                        <input type="file" wire:model="logo1" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('logo1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if ($logo1) <img src="{{ $logo1->temporaryUrl() }}" class="mt-2 h-20 object-contain rounded"> @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo 2 (Optional)</label>
                        <input type="file" wire:model="logo2" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('logo2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if ($logo2) <img src="{{ $logo2->temporaryUrl() }}" class="mt-2 h-20 object-contain rounded"> @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo 3 (Optional)</label>
                        <input type="file" wire:model="logo3" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('logo3') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if ($logo3) <img src="{{ $logo3->temporaryUrl() }}" class="mt-2 h-20 object-contain rounded"> @endif
                    </div>
                </div>
            </div>

            <div x-data="{
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
                    wire:model="description"
                    rows="3"
                    placeholder="Explain the purpose of this form..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                           wire:model="min_age"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('min_age') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                            wire:model="max_age"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('max_age') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
                        Select locations
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
                                                <div class="text-gray-500 text-xs mt-0.5 wrap-break-word">{{ $location->city }} · {{ $location->region }}</div>
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
                Formulaires associés
            </h2>
            <div class="rounded-xl border-2 border-dashed border-indigo-200 bg-indigo-50 p-6 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                    <i class="ri-information-line text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-indigo-800 mb-1">Associer des formulaires après la création</p>
                    <p class="text-xs text-indigo-600">Sauvegardez d'abord le projet. Vous serez redirigé vers la page d'édition où vous pourrez attacher des formulaires, définir leurs ordres et statuts.</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">

            <a href="{{ route('admin.programe') }}"
               class="px-5 py-2.5 border rounded-lg text-gray-600 hover:bg-gray-100">
                Cancel
            </a>

            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <span wire:loading.remove>Save Project</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded');
        console.log('Livewire available:', typeof Livewire !== 'undefined');
        
        if (typeof Livewire !== 'undefined') {
            console.log('Livewire is loaded');
        } else {
            console.error('Livewire is NOT loaded!');
        }
    });
</script>
