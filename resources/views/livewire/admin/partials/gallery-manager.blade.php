@props(['title' => 'Galerie du slider'])

{{--
    Shared multi-image manager for Actualité / Article / Projet / CLC / ZETTAT.
    Expects the Livewire component to use App\Livewire\Concerns\ManagesGallery.
--}}
<div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm shadow-slate-200">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">{{ $title }}</p>
            <p class="mt-2 text-[0.72rem] leading-relaxed text-slate-400">
                La couverture reste la première image. Ajoutez ici d'autres images : elles apparaîtront dans le slider public.
            </p>
        </div>
        <span class="shrink-0 rounded-full bg-[#EAECEF] px-3 py-1 text-[0.65rem] font-extrabold text-slate-500">
            {{ count($gallery) }} / 12
        </span>
    </div>

    @if(count($gallery) > 0)
        <div class="mt-5 grid grid-cols-2 gap-3">
            @foreach($gallery as $index => $path)
                <div wire:key="gallery-image-{{ md5((string) $path) }}-{{ $index }}"
                     class="group relative overflow-hidden rounded-[1.25rem] border border-slate-200 bg-slate-100">
                    <img src="{{ $this->mediaUrl($path) }}" alt="Image {{ $index + 1 }} du slider"
                         class="h-28 w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">

                    <span class="absolute left-2 top-2 rounded-full bg-black/55 px-2 py-0.5 text-[0.6rem] font-extrabold text-white backdrop-blur-sm">
                        {{ $index + 1 }}
                    </span>

                    <div class="absolute inset-x-0 bottom-0 flex items-center justify-center gap-1.5 bg-gradient-to-t from-black/75 to-transparent px-2 pb-2 pt-6">
                        <button type="button" wire:click="moveGalleryImage({{ $index }}, -1)"
                                @disabled($index === 0)
                                class="inline-flex size-7 items-center justify-center rounded-full bg-white/90 text-slate-700 transition hover:bg-white disabled:cursor-not-allowed disabled:opacity-40"
                                title="Déplacer vers la gauche">
                            <i class="ri-arrow-left-s-line text-sm"></i>
                        </button>
                        <button type="button" wire:click="removeGalleryImage({{ $index }})"
                                class="inline-flex size-7 items-center justify-center rounded-full bg-rose-500 text-white transition hover:bg-rose-600"
                                title="Supprimer du slider">
                            <i class="ri-delete-bin-6-line text-xs"></i>
                        </button>
                        <button type="button" wire:click="moveGalleryImage({{ $index }}, 1)"
                                @disabled($index === count($gallery) - 1)
                                class="inline-flex size-7 items-center justify-center rounded-full bg-white/90 text-slate-700 transition hover:bg-white disabled:cursor-not-allowed disabled:opacity-40"
                                title="Déplacer vers la droite">
                            <i class="ri-arrow-right-s-line text-sm"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if(count($newGallery) > 0)
        <div class="mt-5">
            <p class="mb-3 text-[0.65rem] font-extrabold uppercase tracking-widest text-[#214f95]">Nouvelles images en attente</p>
            <div class="grid grid-cols-2 gap-3">
                @foreach($newGallery as $index => $file)
                    <div wire:key="gallery-new-{{ $index }}" class="group relative overflow-hidden rounded-[1.25rem] border border-[#214f95]/30 bg-slate-100">
                        <img src="{{ $file->temporaryUrl() }}" alt="Nouvelle image {{ $index + 1 }}"
                             class="h-28 w-full object-cover" loading="lazy">
                        <span class="absolute left-2 top-2 rounded-full bg-[#214f95] px-2 py-0.5 text-[0.6rem] font-extrabold text-white">
                            Nouvelle
                        </span>
                        <button type="button" wire:click="removeNewGalleryImage({{ $index }})"
                                class="absolute right-2 top-2 inline-flex size-7 items-center justify-center rounded-full bg-rose-500 text-white transition hover:bg-rose-600"
                                title="Retirer cette image">
                            <i class="ri-close-line text-sm"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <label class="mt-5 flex cursor-pointer flex-col items-center justify-center rounded-[1.25rem] border-2 border-dashed border-slate-200 bg-[#FAFAFC] py-6 text-center transition hover:border-[#82E682] hover:bg-[#f0fbf0] group">
        <div class="mb-2 flex size-12 items-center justify-center rounded-full bg-white shadow-sm transition group-hover:bg-[#82E682]/10">
            <i class="ri-gallery-upload-line text-xl text-slate-400 transition group-hover:text-[#23803B]"></i>
        </div>
        <p class="text-sm font-bold text-[#0B1528]">Ajouter des images au slider</p>
        <p class="mt-1 text-[0.7rem] text-slate-400">JPG, PNG ou WebP — 2 Mo max par image · sélection multiple</p>
        <input type="file" wire:model="newGallery" accept="image/jpeg,image/png,image/webp" multiple class="hidden">
    </label>

    <div wire:loading wire:target="newGallery" class="mt-2 text-xs font-semibold text-[#214f95]">
        Téléversement des images en cours…
    </div>

    @error('newGallery.*')
        <p class="mt-2 text-xs font-medium text-rose-500">{{ $message }}</p>
    @enderror
</div>
