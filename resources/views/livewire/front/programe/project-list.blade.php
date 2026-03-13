@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div class="min-h-screen bg-gray-50 py-8" @if($isArabic) dir="rtl" @endif>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $tr('Projets disponibles', 'المشاريع المتاحة') }}</h1>
            <p class="text-gray-600">{{ $tr('Parcourez et participez aux projets qui correspondent à votre profil', 'تصفح وشارك في المشاريع التي تناسب ملفك الشخصي') }}</p>
        </div>



        <!-- Projects Grid -->
        @if($projects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($projects as $project)
                    @php
                        $eligibility = $eligibilityMap[$project->id] ?? ['eligible' => true, 'reasons' => []];
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden group">
                        <!-- Card Header -->
                        <div class="h-2 bg-linear-to-r from-blue-500 to-purple-500"></div>
                        
                        <div class="p-6">
                            <!-- Project Name -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition">
                                {{ $project->project_name }}
                            </h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $project->description }}
                            </p>

                            <!-- Stats -->
                            <div class="flex items-center justify-between mb-6 text-sm">
                                <div class="flex items-center gap-2 text-blue-600">
                                    <i class="ri-file-list-line"></i>
                                    <span>{{ $project->formulaires->count() }} {{ $tr('Formulaires', 'استمارات') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="ri-user-line"></i>
                                    <span>{{ $tr('Âge', 'العمر') }}: {{ $project->min_age }}-{{ $project->max_age }}</span>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $eligibility['eligible'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' }}">
                                    {{ $eligibility['eligible'] ? $tr('Éligible', 'مؤهل') : $tr('Non éligible', 'غير مؤهل') }}
                                </span>
                            </div>

                            @if(!$eligibility['eligible'])
                                <div class="mb-4 text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg p-2.5">
                                    @foreach($eligibility['reasons'] as $reason)
                                        <div>• {{ $reason }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Action Button -->
                            @if($eligibility['eligible'])
                                <a href="{{ route('user.project.detail', $project->id) }}"
                                   class="block w-full px-6 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition font-medium">
                                    {{ $tr('Voir le projet', 'عرض المشروع') }}
                                    <i class="ri-arrow-right-line ml-2"></i>
                                </a>
                            @else
                                <button type="button" disabled
                                    class="block w-full px-6 py-3 bg-gray-300 text-gray-600 text-center rounded-lg font-medium cursor-not-allowed">
                                    {{ $tr('Indisponible pour votre profil', 'غير متاح لملفك الشخصي') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="ri-folder-open-line text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ $tr('Aucun projet trouvé', 'لم يتم العثور على مشاريع') }}</h3>
                <p class="text-gray-500">
                    @if($search)
                        {{ $tr('Aucun projet ne correspond à votre recherche. Essayez d\'autres mots-clés.', 'لا يوجد مشروع يطابق بحثك. جرب كلمات أخرى.') }}
                    @else
                        {{ $tr('Il n\'y a actuellement aucun projet actif correspondant à votre profil.', 'لا توجد مشاريع نشطة مطابقة لملفك الشخصي حاليًا.') }}
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
