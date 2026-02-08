<div class="p-8 bg-gray-50 min-h-screen">

    {{-- Component Check --}}
    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-4">
        <p><strong>Component Name:</strong> {{ get_class($this) }}</p>
        <p><strong>Method Exists:</strong> {{ method_exists($this, 'saveProjectList') ? 'YES' : 'NO' }}</p>
    </div>

    {{-- Debug: Show current values --}}
    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4" x-data="{ show: false }">
        <button @click="show = !show" class="text-blue-700 font-semibold">
            Toggle Debug Info
        </button>
        <div x-show="show" class="mt-2 text-sm">
            <p><strong>Project Name:</strong> {{ $project_name ?? 'empty' }}</p>
            <p><strong>Description:</strong> {{ $description ?? 'empty' }}</p>
            <p><strong>Icon:</strong> {{ $icon ?? 'empty' }}</p>
            <p><strong>Min Age:</strong> {{ $min_age ?? 'empty' }}</p>
            <p><strong>Max Age:</strong> {{ $max_age ?? 'empty' }}</p>
            <p><strong>Addresses:</strong> {{ count($allowed_address_id ?? []) }}</p>
        </div>
    </div>

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
                           class="w-full px-4 py-2 border rounded-lg">
                    @error('project_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>
                    <select class="w-full px-4 py-2 border rounded-lg" wire:model="is_active">
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
                        class="w-full px-4 py-2 border rounded-lg">
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
                    class="w-full px-4 py-2 border rounded-lg"></textarea>
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
                           class="w-full px-4 py-2 border rounded-lg">
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
                           class="w-full px-4 py-2 border rounded-lg">
                    @error('max_age') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Address Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Address Source (Multiple)
                    </label>
                    <div class="border rounded-lg p-4 max-h-48 overflow-y-auto space-y-2">
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
                Form Fields (User Inputs)
            </h2>

            <!-- Field Row -->

            <!-- Add Field Button -->
            <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 border rounded-lg hover:bg-gray-100">
                + Add Field
            </button>

            <p class="text-xs text-gray-500 mt-2">
                Address Select will use values from backend address list.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <button type="button"
                    wire:click="testLivewire"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Test Livewire
            </button>

            <a href="{{ route('admin.programe_zettat') }}"
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
