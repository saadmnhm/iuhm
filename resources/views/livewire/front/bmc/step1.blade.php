@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif>
    <div class="field-Resume-executif mt-4">
        <table class="table-auto bmc_table border-collapse border border-gray-300 w-full">
            <tr>
                {{-- Partenariats clés --}}
                <td rowspan="2" class="bmc_td" style="width:20%; height:300px;">
                    <strong>{{ $tr('Partenariats clés', 'الشراكات الرئيسية') }}</strong>
                    <textarea wire:model="partenaires_cles" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('partenaires_cles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>

                {{-- Activités clés --}}
                <td class="bmc_td" style="width:20%; height:150px;">
                    <strong>{{ $tr('Activités clés', 'الأنشطة الرئيسية') }}</strong>
                    <textarea wire:model="activites_cles" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('activites_cles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>

                {{-- Proposition de valeur --}}
                <td rowspan="2" class="bmc_td" style="width:20%; height:300px;">
                    <strong>{{ $tr('Proposition de valeur', 'القيمة المقترحة') }}</strong>
                    <textarea wire:model="proposition_valeur" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('proposition_valeur') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>

                {{-- Relations clients --}}
                <td class="bmc_td" style="width:20%; height:150px;">
                    <strong>{{ $tr('Relations avec les clients', 'علاقات العملاء') }}</strong>
                    <textarea wire:model="relations_clients" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('relations_clients') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>

                {{-- Segments de clientèle --}}
                <td rowspan="2" class="bmc_td" style="width:20%; height:300px;">
                    <strong>{{ $tr('Segments de clientèle', 'شرائح العملاء') }}</strong>
                    <textarea wire:model="segments_clientele" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('segments_clientele') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>
            </tr>

            <tr>
                {{-- Ressources clés --}}
                <td class="bmc_td" style="height:150px;">
                    <strong>{{ $tr('Ressources clés', 'الموارد الرئيسية') }}</strong>
                    <textarea wire:model="ressources_cles" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('ressources_cles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>

                {{-- Canaux --}}
                <td class="bmc_td" style="height:150px;">
                    <strong>{{ $tr('Les Canaux', 'القنوات') }}</strong>
                    <textarea wire:model="canaux" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('canaux') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>
            </tr>

            <tr>
                {{-- Structure des coûts --}}
                <td colspan="3" class="bmc_td" style="height:140px;">
                    <strong>{{ $tr('Structure des coûts', 'هيكل التكاليف') }}</strong>
                    <textarea wire:model="structure_couts" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('structure_couts') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>

                {{-- Flux de revenus --}}
                <td colspan="2" class="bmc_td" style="height:140px;">
                    <strong>{{ $tr('Flux de revenus', 'مصادر الإيرادات') }}</strong>
                    <textarea wire:model="flux_revenus" class="bmc_textarea" @if($isReadOnly) readonly @endif></textarea>
                    @error('flux_revenus') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </td>
            </tr>
        </table>
    </div>
</div>
