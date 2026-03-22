@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;

    $sections = [
        'organisation' => [
            'title' => $tr('Contenu de la formation', 'محتوى التكوين'),
            'questions' => [
                'q1' => $tr('Le contenu de la formation était clair et bien structuré', 'كان محتوى التكوين واضحاً ومُنظْماً بشكل جيد  '),
                'q2' => $tr('Les thèmes abordés étaient pertinents et utiles pour mon projet entrepreneurial', '  كانت المواضيع المطروحة دات صلة ومفيذة لمشروعي الريادي'),
            ]
        ],
        'formateur' => [
            'title' => $tr('Formateur/formatrice', 'المكون / المكونة'),
            'questions' => [
                'q3' => $tr('Le formateur maîtrisait bien les sujets abordés', 'كان المكون ملما جيدا بالمواضيع التي تم تناولها'),
                'q4' => $tr('Les explications et les exemples fournis étaient clairs, satisfaisants et pertinents.', 'كانت الشروحات والأمثلة المقدمة واضحة ومناسبة'),
            ]
        ],
        'contenu' => [
            'title' => $tr('Organisation', 'التنظيم'),
            'questions' => [
                'q5' => $tr('L\'organisation générale de la formation était satisfaisante', 'كان التنظيم العام للتكوين مرضيا'),
                'q6' => $tr('Les supports de formation étaient utiles et pertinents', ' كانت وسائل التكوين مفيدة وذات صلة'),
            ]
        ],
        'resulta' => [
            'title' => $tr('Résultats', 'النتائج'),
            'questions' => [
                'q7' => $tr('Cette formation a répondu à mes attentes','لبي هذا التكوين توقعاتي'),
                'q8' => $tr('Je me sens plus en confiance pour développer ou gérer mon projet entrepreneurial','أشعر بثقة أكبر لتطوير أو إدارة مشروعي الريادي'),
            ]
        ],
    ];
@endphp

<div class="min-h-screen " @if($isArabic) dir="rtl" @endif>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- SUCCESS --}}
        @if (session()->has('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERRORS --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm">
                <ul class="list-disc ps-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="mb-6 flex items-center justify-between gap-3">
            <a href="{{ route('user.project.detail', ['id' => $projectId]) }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">
                <i class="ri-arrow-left-line"></i>
                {{ $tr('Retour au projet', 'العودة إلى المشروع') }}
            </a>

            <span class="inline-flex items-center gap-2 rounded-full border border-green-200 bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                <i class="ri-feedback-line"></i>
                {{ $tr('Avis de formation', 'رأي التكوين') }}
            </span>
        </div>

        {{-- CARD --}}
        <div class="relative overflow-hidden rounded-2xl border border-green-100 bg-white shadow-xl">

            <div class="relative px-6 sm:px-8 py-8 sm:py-10" x-data="{ hoverStar: 0 }">

                {{-- TITLE --}}
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2">
                    {{ $tr('Donner mon avis', 'إعطاء رأيي') }}
                </h1>

                <p class="text-sm sm:text-base text-slate-600 mb-8">
                    {{ $tr('Partagez votre expérience de formation.', 'شارك تجربتك في التكوين.') }}
                </p>

                {{-- ⭐ GLOBAL RATING --}}
                <div class="mb-8 rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-3">
                        {{ $tr('Votre note globale', 'تقييمك العام') }} *
                    </label>

                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                wire:click="$set('reviewRating', {{ $i }})"
                                @mouseenter="hoverStar = {{ $i }}"
                                @mouseleave="hoverStar = 0"
                                class="w-11 h-11 rounded-xl border-2 flex items-center justify-center transition"
                                :class="((hoverStar > 0 ? hoverStar : @js((int)($reviewRating ?? 0))) >= {{ $i }}) 
                                ? 'bg-green-500 border-green-500 text-white shadow' 
                                : 'bg-white border-slate-200 text-slate-400 hover:border-green-300'">

                                <i class="ri-star-fill text-lg"></i>
                            </button>
                        @endfor

                        <span class="text-sm text-slate-500 ms-2">
                            {{ $reviewRating ? $reviewRating.'/5' : $tr('Choisissez', 'اختر') }}
                        </span>
                    </div>
                </div>

                {{-- QUESTIONS --}}
                <div class="mb-8 space-y-6">
                    @foreach($sections as $sectionKey => $section)

                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                            {{-- SECTION TITLE --}}
                            <div class=" px-5 py-3 border-b border-slate-200">
                                <h2 class="text-sm font-bold text-green-800 uppercase tracking-wide">
                                    {{ $section['title'] }}
                                </h2>
                            </div>

                            {{-- QUESTIONS --}}
                            <div class="p-5 space-y-6">

                                @foreach($section['questions'] as $qKey => $question)

                                    <div>
                                        <p class="text-sm font-semibold text-slate-700 mb-3">
                                            {{ $question }}
                                        </p>

                                        <div class="flex flex-wrap gap-3">

                                            @foreach([
                                                1 => $tr('Insatisfait', 'غير راضٍ'),
                                                2 => $tr('Satisfait', 'راضٍ'),
                                                3 => $tr('Très satisfait', 'راضٍ جداً'),
                                            ] as $value => $label)

                                                <label class="cursor-pointer group">
                                                    <input type="radio"
                                                        name="answers[{{ $qKey }}]"
                                                        wire:model="answers.{{ $qKey }}"
                                                        value="{{ $value }}"
                                                        class="hidden peer">

                                                    <div class="px-4 py-2 rounded-lg border border-slate-300 
                                                        text-sm font-medium
                                                        peer-checked:bg-green-500 
                                                        peer-checked:text-white 
                                                        peer-checked:border-green-500
                                                        group-hover:border-green-400
                                                        transition">

                                                        {{ $label }}
                                                    </div>
                                                </label>

                                            @endforeach

                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>

                    @endforeach
                </div>

                {{-- COMMENT --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        {{ $tr('Commentaire', 'التعليق') }}
                    </label>

                    <textarea wire:model="reviewFeedback"
                        rows="5"
                        class="w-full rounded-xl p-3 border-slate-300 shadow-sm border 
                               focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                        placeholder="{{ $tr('Décrivez votre avis...', 'اكتب رأيك...') }}">
                    </textarea>
                </div>

                {{-- ACTIONS --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('user.project.detail', ['id' => $projectId]) }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 transition">
                        {{ $tr('Annuler', 'إلغاء') }}
                    </a>

                    <button type="button"
                        wire:click="saveReview"
                        wire:loading.attr="disabled"
                        class="px-6 py-3 rounded-xl bg-green-500 hover:bg-green-600 
                               text-white font-semibold shadow-lg shadow-green-200 
                               transition flex items-center gap-2">

                        <span wire:loading.remove>
                            <i class="ri-send-plane-fill"></i>
                            {{ $tr('Enregistrer', 'حفظ') }}
                        </span>

                        <span wire:loading>
                            {{ $tr('Traitement...', 'جار المعالجة...') }}
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>