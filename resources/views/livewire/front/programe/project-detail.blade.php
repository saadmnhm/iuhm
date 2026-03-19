@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif>

    @php
        $totalForms     = count($formulaires);
        $completedForms = collect($formulaires)->where('is_submitted', true)->count();
        $pendingForms   = $totalForms - $completedForms;
        $progress       = $totalForms > 0 ? ($completedForms / $totalForms) * 100 : 0;
        $allDone        = $completedForms === $totalForms && $totalForms > 0;
    @endphp

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header actions -->
    <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
        <a href="" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <i class="ri-arrow-left-s-line"></i> {{ $tr('Retour aux programmes', 'العودة إلى البرامج') }}
        </a>
        <div class="flex gap-2">
            @if($projectSubmission && $projectSubmission->formation_review_rating)
            <a href="{{ route('user.project.print.review', ['id' => auth()->guard('candidat')->id(), 'projectId' => $project->id]) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <i class="ri-printer-line"></i> {{ $tr('Avis de formation', 'رأي التكوين') }}
            </a>
            @endif
        </div>
    </div>

    @if($projectSubmission && $projectSubmission->require_formation_review && !$projectSubmission->formation_review_rating)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg shadow-sm flex justify-between items-center">
        <div>
            <h3 class="text-yellow-800 font-bold">{{ $tr('Avis de Formation Requis', 'مطلوب رأي التكوين') }}</h3>
            <p class="text-sm text-yellow-700 mt-1">
                {{ $tr('L\'administration a demandé votre avis sur la formation. Veuillez remplir ce formulaire pour continuer vos soumissions.', 'طلبت الإدارة رأيك في التكوين. يرجى ملء هذا النموذج لمتابعة تقديماتك.') }}
            </p>
        </div>
        <a href="{{ route('user.project.review', ['id' => $project->id]) }}"
           class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold rounded-lg transition whitespace-nowrap">
            <i class="ri-feedback-line mr-1"></i> {{ $tr('Donner mon avis', 'إعطاء رأيي') }}
        </a>
    </div>
    @endif

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">{{ $tr('Total Formulaires', 'إجمالي الاستمارات') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalForms }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ri-file-list-3-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">{{ $tr('Complétés', 'مكتملة') }}</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $completedForms }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ri-checkbox-circle-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">{{ $tr('En attente', 'قيد الانتظار') }}</p>
                    <p class="text-3xl font-bold text-red-500 mt-2">{{ $pendingForms }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="ri-time-line text-red-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">{{ $tr('Progression', 'نسبة التقدم') }}</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ round($progress) }}%</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="ri-pie-chart-line text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- All-done banner -->
    @if($allDone)
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center shrink-0">
            <i class="ri-trophy-fill text-green-600 text-xl"></i>
        </div>
        <div>
            <div class="font-bold text-green-800">{{ $tr('Félicitations ! Dossier complet', 'تهانينا! ملفك مكتمل') }}</div>
            <p class="text-sm text-green-600 mt-0.5">{{ $tr('Tous vos formulaires ont été soumis. Nous reviendrons vers vous prochainement.', 'تم إرسال جميع استماراتك. سنعاود التواصل معك قريبًا.') }}</p>
        </div>
    </div>
    @endif

    <!-- Programme Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                        <i class="ri-folder-3-line mr-1"></i>{{ $tr('Programme', 'برنامج') }}
                    </span>
                    @if($project->status === 'Active')
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700">
                            <i class="ri-radio-button-line mr-1"></i>{{ $tr('Actif', 'نشط') }}
                        </span>
                    @endif
                </div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $project->project_name }}</h3>
                @if($project->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $project->description }}</p>
                @endif
            </div>
            @if($totalForms > 0)
            <div class="flex items-center gap-3">
                <div class="w-36 bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-500 {{ $allDone ? 'bg-green-500' : 'bg-blue-500' }}"
                         style="width: {{ $progress }}%"></div>
                </div>
                <span class="text-sm font-medium text-gray-600">{{ $completedForms }}/{{ $totalForms }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Formulaires Section -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="ri-list-check-2 mr-2"></i>{{ $tr('Formulaires requis', 'الاستمارات المطلوبة') }} ({{ $totalForms }})
        </h3>

        @if(count($formulaires) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($formulaires as $index => $formulaire)
                @php
                    $canStart = $formulaire['can_start'] ?? true;
                    $isNext = ($currentFormulaireIndex === $index);
                    $color  = $formulaire['color'];
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200 {{ !$canStart ? 'opacity-50' : '' }}">
                    <div class="p-6">
                        <!-- Icon and Title -->
                        <div class="flex items-center mb-4 pb-4 border-b border-gray-100">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center mr-4 shrink-0 relative"
                                 style="background: {{ $formulaire['is_submitted'] == true ? '#dcfce7' : ($canStart ? $color.'18' : '#f3f4f6') }};
                                        border: 2px solid {{ $formulaire['is_submitted'] == true ? '#22c55e' : ($canStart ? $color : '#d1d5db') }};">
                                @if($formulaire['is_submitted'] == true)
                                    <i class="ri-checkbox-circle-fill text-2xl text-green-500"></i>
                                @elseif(!$canStart)
                                    <i class="ri-lock-2-fill text-2xl text-gray-400"></i>
                                @else
                                    <i class="{{ $formulaire['icon'] }} text-2xl" style="color: {{ $color }};"></i>
                                @endif
                                <!-- Step badge -->
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-base font-semibold text-gray-900 truncate">{{ $formulaire['title'] }}</div>
                                @if($formulaire['title_ar'])
                                    <div class="text-sm text-gray-500 truncate" dir="rtl">{{ $formulaire['title_ar'] }}</div>
                                @endif
                                <div class="flex flex-wrap items-center gap-1 mt-1">
                                    @if($formulaire['is_required'])
                                        <span class="inline-block px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium">{{ $tr('Requis', 'إلزامي') }}</span>
                                    @endif
                                    @if($formulaire['has_introduction'])
                                        <span class="inline-block px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded-full font-medium">
                                            <i class="ri-book-open-line mr-0.5"></i>{{ $tr('Intro', 'مقدمة') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            @if($formulaire['is_submitted'] == true)
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    <i class="ri-checkbox-circle-line mr-1"></i>{{ $tr('Complété', 'مكتمل') }}
                                </span>
                                <div class="text-xs text-gray-400 mt-1.5">
                                    <i class="ri-calendar-check-line mr-1"></i>{{ \Carbon\Carbon::parse($formulaire['submitted_at'])->format('d M Y') }}
                                </div>
                            @elseif(!$canStart)
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                    <i class="ri-lock-2-line mr-1"></i>{{ $tr('Verrouillé', 'مغلق') }}
                                </span>
                                <div class="text-xs text-gray-400 mt-1.5">
                                    <i class="ri-information-line mr-1"></i>{{ $formulaire['lock_reason'] ?? $tr('Complétez les étapes précédentes', 'أكمل المراحل السابقة') }}
                                </div>
                            @elseif($isNext)
                                <span class="px-3 py-1 text-xs font-medium rounded-full"
                                      style="background: {{ $color }}18; color: {{ $color }};">
                                    <i class="ri-arrow-right-circle-line mr-1"></i>{{ $tr('Prochaine étape', 'المرحلة التالية') }}
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                    <i class="ri-file-edit-line mr-1"></i>{{ $tr('Disponible', 'متاح') }}
                                </span>
                            @endif
                        </div>

                        <!-- Action Button -->
                        <div>
                            @if($formulaire['is_submitted'] == true)
                                <button wire:click="startFormulaire({{ $index }})"
                                        class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 text-center">
                                    <i class="ri-eye-line mr-1"></i>{{ $tr('Voir', 'عرض') }}
                                </button>
                            @elseif($canStart)
                                <button wire:click="startFormulaire({{ $index }})"
                                        wire:loading.attr="disabled"
                                        wire:target="startFormulaire({{ $index }})"
                                        class="w-full px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors duration-200 text-center flex items-center justify-center gap-2"
                                        style="background: {{ $color }};">
                                    <span wire:loading.remove wire:target="startFormulaire({{ $index }})">
                                        <i class="{{ $isNext ? 'ri-play-circle-fill' : 'ri-edit-2-line' }} mr-1"></i>
                                        {{ $isNext ? $tr('Commencer', 'ابدأ') : $tr('Remplir', 'تعبئة') }}
                                    </span>
                                    <span wire:loading wire:target="startFormulaire({{ $index }})">
                                        <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                    </span>
                                </button>
                            @else
                                <button disabled
                                        class="w-full px-4 py-2 bg-gray-200 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed text-center">
                                     <i class="ri-lock-2-line mr-1"></i>{{ $tr('Verrouillé', 'مغلق') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

        @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <i class="ri-file-list-3-line text-gray-400 text-5xl mb-4 block"></i>
            <p class="text-gray-500">{{ $tr("Ce programme n'a pas encore de formulaires attachés.", 'لا توجد استمارات مرتبطة بهذا البرنامج بعد.') }}</p>
        </div>
        @endif
    </div>

</div>
</div>
