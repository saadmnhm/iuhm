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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Icon *
                </label>
                <input type="text"
                        required
                        wire:model="icon"
                        placeholder="e.g. ri-file-list-3-line"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('icon') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
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
            <button type="button"
                    wire:click="testLivewire"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Test Livewire
            </button>

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
