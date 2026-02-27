<div class="max-w-5xl mx-auto">

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3">
        <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="flex gap-6">
        {{-- ═══ Category Sidebar ═══ --}}
        <div class="w-56 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-indigo-800"><i class="ri-settings-3-line mr-1"></i> Catégories</h3>
                </div>
                <div class="p-2 space-y-1">
                    @php
                        $catIcons = [
                            'general' => 'ri-building-line',
                            'contact' => 'ri-contacts-line',
                            'finance' => 'ri-money-euro-circle-line',
                            'rh'      => 'ri-team-line',
                            'seo'     => 'ri-search-eye-line',
                        ];
                    @endphp
                    @foreach($categories as $key => $label)
                    <button wire:click="switchCategory('{{ $key }}')"
                            class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                            {{ $activeCategory === $key ? 'bg-indigo-100 text-indigo-800' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="{{ $catIcons[$key] ?? 'ri-settings-line' }} text-base"></i>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══ Parameters Form ═══ --}}
        <div class="flex-1">
            <form wire:submit.prevent="save" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-indigo-800">
                        {{ $categories[$activeCategory] ?? 'Paramètres' }}
                    </h3>
                    <span class="text-xs text-gray-500">{{ $params->count() }} paramètres</span>
                </div>

                <div class="p-6 space-y-5">
                    @foreach($params as $param)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            {{ $param->label }}
                            <span class="text-xs text-gray-400 font-normal ml-1">({{ $param->key }})</span>
                        </label>

                        @if($param->type === 'textarea')
                            <textarea wire:model="formData.{{ $param->key }}" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none transition">{{ $formData[$param->key] ?? '' }}</textarea>

                        @elseif($param->type === 'select')
                            <select wire:model="formData.{{ $param->key }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                                <option value="">-- Sélectionner --</option>
                                @foreach($param->options ?? [] as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>

                        @elseif($param->type === 'boolean')
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="formData.{{ $param->key }}"
                                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="text-sm text-gray-600">Activé</span>
                            </label>

                        @elseif($param->type === 'number')
                            <input type="number" wire:model="formData.{{ $param->key }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">

                        @elseif($param->type === 'email')
                            <input type="email" wire:model="formData.{{ $param->key }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">

                        @elseif($param->type === 'url')
                            <input type="url" wire:model="formData.{{ $param->key }}" placeholder="https://..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">

                        @else
                            <input type="text" wire:model="formData.{{ $param->key }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none transition">
                        @endif

                        @if($param->updater)
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="ri-user-line"></i> Modifié par {{ $param->updater->name }} le {{ $param->updated_at->format('d/m/Y H:i') }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                        <i class="ri-save-line mr-1"></i> Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
