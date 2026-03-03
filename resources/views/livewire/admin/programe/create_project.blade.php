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
                    <span class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-lg flex-shrink-0"
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

                <!-- Address Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Address Source (Multiple)
                    </label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto space-y-2">
                        @foreach($addresses as $address)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" 
                                       wire:model="allowed_address_id" 
                                       value="{{ $address->id }}"
                                       class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">
                                    {{ $address->address_line1 }} - {{ $address->city }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @if(empty($allowed_address_id))
                        <p class="text-xs text-gray-500 mt-1">No addresses selected (allows all)</p>
                    @else
                        <p class="text-xs text-blue-600 mt-1">{{ count($allowed_address_id) }} address(es) selected</p>
                    @endif
                    @error('allowed_address_id') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 3: Form Fields (Champs) -->
        <div class="mb-10">
            <h2 class="text-lg font-medium text-gray-800 mb-4">
                Formulaires associés
            </h2>
            <div class="rounded-xl border-2 border-dashed border-indigo-200 bg-indigo-50 p-6 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
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
