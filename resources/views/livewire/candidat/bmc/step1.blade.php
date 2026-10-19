@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif class="space-y-4">
    <div class="grid grid-cols-5 gap-4">
        {{-- Partenariats clés --}}
        <div class="col-span-1 row-span-2 h-[300px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Partenariats clés', 'الشراكات الرئيسية') }}</strong>
            <textarea wire:model="partenaires_cles" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('partenaires_cles') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Activités clés --}}
        <div class="col-span-1 h-[150px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Activités clés', 'الأنشطة الرئيسية') }}</strong>
            <textarea wire:model="activites_cles" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('activites_cles') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Proposition de valeur --}}
        <div class="col-span-1 row-span-2 h-[300px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Proposition de valeur', 'القيمة المقترحة') }}</strong>
            <textarea wire:model="proposition_valeur" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('proposition_valeur') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Relations clients --}}
        <div class="col-span-1 h-[150px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Relations avec les clients', 'علاقات العملاء') }}</strong>
            <textarea wire:model="relations_clients" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('relations_clients') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Segments de clientèle --}}
        <div class="col-span-1 row-span-2 h-[300px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Segments de clientèle', 'شرائح العملاء') }}</strong>
            <textarea wire:model="segments_clientele" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('segments_clientele') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-5 gap-4">
        {{-- Ressources clés --}}
        <div class="col-span-1 h-[150px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Ressources clés', 'الموارد الرئيسية') }}</strong>
            <textarea wire:model="ressources_cles" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('ressources_cles') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Canaux --}}
        <div class="col-span-1 h-[150px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Les Canaux', 'القنوات') }}</strong>
            <textarea wire:model="canaux" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('canaux') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Structure des coûts --}}
        <div class="col-span-3 h-[140px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Structure des coûts', 'هيكل التكاليف') }}</strong>
            <textarea wire:model="structure_couts" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('structure_couts') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- Flux de revenus --}}
        <div class="col-span-2 h-[140px] bg-white rounded-xl border border-gray-200 p-4">
            <strong class="block text-sm font-semibold text-gray-900 mb-2">{{ $tr('Flux de revenus', 'مصادر الإيرادات') }}</strong>
            <textarea wire:model="flux_revenus" class="w-full h-full iuhm_textarea rounded-lg border-gray-200 resize-none" @if($isReadOnly) readonly @endif></textarea>
            @error('flux_revenus') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
        </div>
    </div>
</div>