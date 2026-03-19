@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div class="min-h-screen bg-gradient-to-b from-amber-50 via-white to-slate-100" @if($isArabic) dir="rtl" @endif>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if (session()->has('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
                <ul class="list-disc ps-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between gap-3">
            <a href="{{ route('user.project.detail', ['id' => $projectId]) }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                <i class="ri-arrow-left-line"></i>
                {{ $tr('Retour au projet', 'العودة إلى المشروع') }}
            </a>
            <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                <i class="ri-feedback-line"></i>
                {{ $tr('Avis de formation', 'رأي التكوين') }}
            </span>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-amber-100 bg-white shadow-xl">
            <div class="absolute -top-20 -right-16 w-56 h-56 rounded-full bg-amber-100 blur-3xl opacity-60"></div>
            <div class="absolute -bottom-20 -left-16 w-56 h-56 rounded-full bg-sky-100 blur-3xl opacity-60"></div>

            <div class="relative px-6 sm:px-8 py-8 sm:py-10" x-data="{ hoverStar: 0 }">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2">
                    {{ $tr('Donner mon avis', 'إعطاء رأيي') }}
                </h1>
                <p class="text-sm sm:text-base text-slate-600 mb-8">
                    {{ $tr('Partagez votre expérience de formation. Vous pouvez joindre plusieurs fichiers.', 'شارك تجربتك في التكوين. يمكنك إرفاق عدة ملفات.') }}
                </p>

                <div class="mb-8 rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-3">
                        {{ $tr('Votre note', 'تقييمك') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    wire:click="$set('reviewRating', {{ $i }})"
                                    @mouseenter="hoverStar = {{ $i }}"
                                    @mouseleave="hoverStar = 0"
                                    class="w-11 h-11 rounded-xl border-2 transition-all duration-150 flex items-center justify-center"
                                    :class="((hoverStar > 0 ? hoverStar : @js((int)($reviewRating ?? 0))) >= {{ $i }}) ? 'border-amber-400 bg-amber-400 text-white shadow' : 'border-slate-200 bg-white text-slate-400 hover:border-amber-200'">
                                <i class="ri-star-fill text-lg"></i>
                            </button>
                        @endfor
                        <span class="text-sm text-slate-500 ms-2">
                            {{ $reviewRating ? $tr('Note: ', 'النتيجة: ') . $reviewRating . '/5' : $tr('Sélectionnez une note', 'اختر تقييمًا') }}
                        </span>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        {{ $tr('Commentaire', 'التعليق') }}
                    </label>
                    <textarea wire:model="reviewFeedback"
                              rows="5"
                              class="w-full rounded-xl border-slate-300 shadow-sm focus:border-amber-400 focus:ring-amber-400"
                              placeholder="{{ $tr('Décrivez votre avis sur la formation...', 'اكتب رأيك حول التكوين...') }}"></textarea>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('user.project.detail', ['id' => $projectId]) }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-medium hover:bg-slate-100 transition">
                        {{ $tr('Annuler', 'إلغاء') }}
                    </a>
                    <button type="button"
                            wire:click="saveReview"
                            wire:loading.attr="disabled"
                            wire:target="saveReview"
                            class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-lg shadow-amber-200 transition">
                        <span wire:loading.remove wire:target="saveReview">
                            <i class="ri-send-plane-fill me-1"></i>{{ $tr('Enregistrer mon avis', 'حفظ رأيي') }}
                        </span>
                        <span wire:loading wire:target="saveReview">
                            {{ $tr('Traitement...', 'جار المعالجة...') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
