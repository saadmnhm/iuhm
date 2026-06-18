@push('head-scripts')
<style>
    /* ── Preview w Typography dyal l-Content ── */
    .prose-preview, .editor-textarea { 
        min-height: 460px !important; 
        padding: 2rem 2.25rem !important; 
        line-height: 1.85 !important; 
        color: #4A5568 !important; 
        font-family: inherit !important; 
        font-size: 16px !important; 
    }
    .prose-preview h2 { font-size: 1.875rem; font-weight: 700; color: #0B1528; margin-top: 2.5rem; margin-bottom: 1.25rem; line-height: 1.2; }
    .prose-preview h3 { font-size: 1.375rem; font-weight: 700; color: #0B1528; margin-top: 2rem; margin-bottom: 0.75rem; }
    .prose-preview p { margin-bottom: 1.25rem; }
    .prose-preview a { color: #214f95; }
    .prose-preview blockquote { background: #F0F2F5; border-radius: 1.5rem; border-left: 6px solid #82E682; padding: 1.5rem 2rem; margin: 2rem 0; color: #0B1528; font-size: 1.05rem; }
    .prose-preview img { max-width: 100%; border-radius: 1.5rem; }
</style>
@endpush

<div>
    {{-- Toast d'erreur global Livewire --}}
    <div x-data="{ show: false, message: '' }" 
         x-on:error-toast.window="show = true; message = $event.detail; setTimeout(() => show = false, 5000)"
         x-show="show" 
         style="display: none;"
         class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-slate-900 text-white px-5 py-4 rounded-2xl shadow-xl max-w-sm transition-all">
        <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <span class="text-sm font-medium" x-text="message"></span>
        <button type="button" @click="show = false" class="text-slate-400 hover:text-white text-lg ml-auto">&times;</button>
    </div>

    <form wire:submit.prevent="save">
        <div class="px-6 pb-12 pt-8 sm:px-8 sm:pt-10 bg-[#FAFAFC] min-h-screen">

            {{-- ── TOP BAR / HEADER ── --}}
            <div class="mb-10" wire:key="header-section">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <span class="inline-flex items-center rounded-full px-4 py-1.5 text-[0.7rem] font-extrabold uppercase tracking-widest shadow-sm mb-4 {{ $isEditing ? 'bg-amber-100 text-amber-800' : 'bg-[#82E682] text-[#0a471b]' }}">
                            {{ $isEditing ? 'MODE ÉDITION' : 'VISUALISATION' }}
                        </span>
                        <h2 class="text-[2rem] font-bold text-[#0B1528] leading-[1.1] sm:text-[2.5rem]">
                            {{ $isEditing ? 'Modifier la page À Propos' : $title }}
                        </h2>
                        @if(!$isEditing && $excerpt)
                            <p class="mt-3 text-[1.05rem] text-[#4A5568] max-w-2xl italic leading-relaxed border-l-4 border-slate-300 pl-4">
                                {{ $excerpt }}
                            </p>
                        @endif
                    </div>

                    {{-- Actions Boutons --}}
                    <div class="flex flex-shrink-0 items-center gap-3 sm:pt-2">
                        @if($isEditing)
                            <button type="button" wire:click="cancelEdit"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-[0.85rem] font-bold text-[#4A5568] hover:bg-[#EAECEF] transition shadow-sm">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-[#0B1528] px-7 py-2.5 text-[0.85rem] font-bold text-white hover:bg-[#162D5A] transition shadow-md">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Enregistrer les changements
                            </button>
                        @else
                            <button type="button" wire:click="toggleEdit"
                                    class="inline-flex items-center gap-2 rounded-full bg-[#23803B] px-6 py-2.5 text-[0.85rem] font-bold text-white hover:bg-[#1b632e] transition shadow-md">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Modifier la page
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Flash Session Success --}}
            @if(session()->has('success'))
                <div class="mb-6 flex items-center gap-3 rounded-[1.5rem] border border-[#82E682]/50 bg-[#f0fbf0] px-5 py-4 text-sm text-[#0a471b] shadow-sm" wire:key="flash-success">
                    <svg class="w-5 h-5 text-[#23803B] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ── MAIN CONTENT GRID ── --}}
            <div class="grid gap-6 lg:grid-cols-3">

                {{-- BLOC GAUCHE (2/3): Textes & Formulaires --}}
                <div class="space-y-6 lg:col-span-2">
                    
                    @if($isEditing)
                        <div class="space-y-6" wire:key="form-editors-group">
                            
                            {{-- Input Titre --}}
                            <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm">
                                <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">Titre Principal</label>
                                <input type="text" wire:model="title" class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-4 text-[1.35rem] font-bold text-[#0B1528] outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 transition">
                                @error('title') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            {{-- Input Résumé --}}
                            <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm">
                                <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-3">Résumé / Introduction courte</label>
                                <textarea wire:model="excerpt" rows="3" maxlength="250" class="w-full rounded-2xl border border-slate-100 bg-[#FAFAFC] px-5 py-3.5 text-[0.95rem] italic text-[#4A5568] outline-none focus:border-[#0B1528] focus:bg-white focus:ring-4 focus:ring-[#0B1528]/8 resize-none transition leading-relaxed"></textarea>
                                @error('excerpt') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            {{-- Input Corps du texte (Rich Text Editor clean ou Standardized Textarea m-styla) --}}
                            <div class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-sm">
                                <div class="border-b border-slate-100 px-6 py-4 bg-[#FAFAFC] flex items-center gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shrink-0"></span>
                                    <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B]">Corps du texte (HTML accepté)</span>
                                </div>
                                <textarea wire:model="content" class="w-full editor-textarea bg-white outline-none border-none resize-y focus:ring-0" placeholder="Rédigez votre contenu ici..."></textarea>
                                @error('content') <p class="px-6 pb-4 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    @else
                        {{-- Mode Rendu / Lecture unique --}}
                        <div class="rounded-[2rem] border border-slate-200/80 bg-white shadow-sm overflow-hidden" wire:key="preview-content-box">
                            <div class="border-b border-slate-100 px-8 py-4 bg-[#FAFAFC] flex items-center justify-between">
                                <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-slate-400">Contenu de la page</span>
                            </div>
                            <div class="prose-preview">
                                {!! $content ?: '<p class="text-slate-400 italic">Aucun contenu rédigé pour le moment. Cliquez sur "Modifier la page" pour commencer.</p>' !!}
                            </div>
                        </div>
                    @endif

                </div>

                {{-- BLOC DROIT (1/3): Image de couverture --}}
                <div class="space-y-6" wire:key="right-sidebar-section">
                    <div class="rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-sm">
                        <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-[#23803B] mb-4">Image Illustrative</p>
                        
                        {{-- Logique dyal Preview --}}
                        @if($newImage)
                            <div class="mb-4 overflow-hidden rounded-[1.25rem] relative h-48 w-full border border-amber-300 shadow-inner" wire:key="img-preview-new">
                                <img src="{{ $newImage->temporaryUrl() }}" class="w-full h-full object-cover" alt="Nouveau template">
                                <span class="absolute top-3 left-3 inline-flex items-center rounded-full bg-amber-400 px-3 py-1 text-[0.6rem] font-extrabold uppercase tracking-widest text-amber-950 shadow-sm">Nouvel aperçu</span>
                            </div>
                        @elseif($image)
                            <div class="mb-4 overflow-hidden rounded-[1.25rem] relative h-48 w-full shadow-sm" wire:key="img-preview-current">
                                <img src="{{ Str::startsWith($image, 'assets/') ? asset($image) : asset('assets/' . $image) }}" class="w-full h-full object-cover" alt="Image À Propos">
                            </div>
                        @else
                            <div class="mb-4 flex items-center justify-center rounded-[1.25rem] bg-slate-50 h-48 border border-dashed border-slate-200 text-slate-400 text-xs italic" wire:key="img-preview-none">
                                Aucune image sélectionnée
                            </div>
                        @endif

                        {{-- Zone de téléversement (Katban gha f mode edit) --}}
                        @if($isEditing)
                            <label class="flex cursor-pointer flex-col items-center justify-center rounded-[1.25rem] border-2 border-dashed border-slate-200 bg-[#FAFAFC] py-6 text-center hover:border-[#82E682] hover:bg-[#f0fbf0] transition group" wire:key="upload-trigger">
                                <div class="flex w-10 h-10 items-center justify-center rounded-full bg-white shadow-sm mb-2 group-hover:bg-[#82E682]/10 transition">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-[#23803B] transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-[#0B1528]">Remplacer l'image</p>
                                <input type="file" wire:model="newImage" accept="image/*" class="hidden">
                            </label>
                            @error('newImage') <p class="mt-2 text-xs text-rose-500 font-medium" wire:key="error-newImage">{{ $message }}</p> @enderror
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>