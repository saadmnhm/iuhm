@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div class="min-h-screen bg-gray-50 py-8 px-4" @if($isArabic) dir="rtl" @endif>
    <div class="max-w-3xl mx-auto">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-8">
                
                <div class="text-center mb-6">
                    <img src="{{ asset('assets/site/images/iuhm_logo.png') }}" alt="Logo IUHM" class="h-20 mx-auto mb-4">
                    <h1 class="text-2xl font-bold text-[#1f3864] underline">{{ $tr('Engagement du bénéficiaire', 'التزام المستفيد(ة)') }}</h1>
                </div>

                <div class="space-y-3 text-gray-700 text-base leading-relaxed">
                    <p><strong>{{ $tr('Programme :', 'البرنامج :') }}</strong> {{ $tr('Soutien à l\'entrepreneuriat des jeunes', 'دعم ريادة الأعمال للشباب') }}</p>
                    <p><strong>{{ $tr('Association :', 'الجمعية :') }}</strong> {{ $tr('Initiative Urbaine', 'المبادرة الحضرية') }}</p>
                    <p><strong>{{ $tr('Lieu :', 'المكان :') }}</strong> {{ $tr('Plateforme Jeunes - Hay Mohammadi', 'منصة الشباب حي المحمدي') }}</p>
                    <p><strong>{{ $tr('Avec le soutien de :', 'البرنامج بدعم من :') }}</strong> {{ $tr('Initiative Nationale pour le Développement Humain', 'المبادرة الوطنية للتنمية البشرية') }}</p>
                </div>

                <hr class="my-6 border-gray-200">

                <h2 class="text-xl font-bold text-[#1f3864] underline text-center mb-4">{{ $tr('Objet du document', 'هدف الوثيقة') }}</h2>
                <p class="text-gray-700 leading-relaxed mb-6">
                    {{ $tr('Cette document vise à clarifier l\'engagement du bénéficiaire à participer aux activités de formation et d\'accompagnement définies dans le cadre du programme de soutien à l\'entrepreneuriat des jeunes, supervisé par l\'Association Initiative Urbaine, avec le soutien de l\'Initiative Nationale pour le Développement Humain.', 'تهدف هذه الوثيقة إلى توضيح التزام المستفيد بالمشاركة في أنشطة التكوين والمواكبة المحددة في إطار برنامج دعم ريادة الأعمال للشباب، الذي تشرف عليه جمعية المبادرة الحضرية، بدعم من المبادرة الوطنية للتنمية البشرية.') }}
                </p>

                <h2 class="text-xl font-bold text-[#1f3864] underline text-center mb-4">{{ $tr('Engagements du bénéficiaire', 'التزامات المستفيد') }}</h2>
                <p class="text-gray-700 mb-4">{{ $tr('Je soussigné(e)', 'أنا الموقع أدناه') }}</p>
                
                <div class="bg-gray-50 rounded-xl p-5 mb-6 space-y-3">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <span class="font-semibold text-gray-900 w-full sm:w-48 flex-shrink-0">{{ $tr('Nom complet :', 'الاسم الكامل :') }}</span>
                        <span class="font-semibold text-gray-900 flex-1 text-center">{{ $candidat->nom }} {{ $candidat->prenom }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <span class="font-semibold text-gray-900 w-full sm:w-48 flex-shrink-0">{{ $tr('CIN :', 'رقم البطاقة الوطنية :') }}</span>
                        <span class="font-semibold text-gray-900 flex-1 text-center">{{ $candidat->cin }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <span class="font-semibold text-gray-900 w-full sm:w-48 flex-shrink-0">{{ $tr('Adresse :', 'العنوان :') }}</span>
                        <span class="font-semibold text-gray-900 flex-1 text-center">
                            {{ $candidat->selected_region }} , {{ $candidat->selected_city }} , {{ $candidat->selected_prefecture }} , {{ $candidat->address_detail }}
                        </span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <span class="font-semibold text-gray-900 w-full sm:w-48 flex-shrink-0">{{ $tr('Téléphone :', 'رقم الهاتف :') }}</span>
                        <span class="font-semibold text-gray-900 flex-1 text-center">{{ $candidat->phone }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                        <span class="font-semibold text-gray-900 w-full sm:w-48 flex-shrink-0">{{ $tr('Date de naissance :', 'تاريخ الازدياد :') }}</span>
                        <span class="font-semibold text-gray-900 flex-1 text-center">{{ $candidat->date_naissance->format('d/m/Y') }}</span>
                    </div>
                </div>

                <p class="text-gray-700 mb-4">{{ $tr('M\'engage à :', 'تعّهد بما يلي') }}</p>
                <ul class="list-disc list-inside space-y-2 text-gray-700 mb-6 pl-4">
                    <li>{{ $tr('Participer activement à toutes les sessions de formation et d\'accompagnement définies dans le cadre du programme', 'المشاركة الفعالة في جميع جلسات التكوين والمواكبة المحددة في إطار البرنامج') }}</li>
                    <li>{{ $tr('Respecter le calendrier établi par l\'association, en assurant la présence et la discipline', 'احترام الجدول الزمني الذي وضعته الجمعية، مع ضمان الحضور والانضباط') }}</li>
                    <li>{{ $tr('Documenter les heures de formation en signant la feuille de présence', 'توثيق الساعات التدريبية من خلال التوقيع على ورقة الحضور') }}</li>
                    <li>{{ $tr('Appliquer les connaissances acquises pour développer son projet entrepreneurial', 'تطبيق المعارف المكتسبة لتطوير مشروعه الريادي') }}</li>
                    <li>{{ $tr('Informer l\'association en cas d\'obstacle ou de difficulté', 'إبلاغ الجمعية في حالة وجود عائق أو صعوبة') }}</li>
                </ul>

                <h2 class="text-xl font-bold text-[#1f3864] underline text-center mb-4">{{ $tr('Engagements de l\'Association Initiative Urbaine', 'التزامات جمعية المبادرة الحضرية') }}</h2>
                <ul class="list-disc list-inside space-y-2 text-gray-700 mb-6 pl-4">
                    <li>{{ $tr('Assurer l\'accompagnement des bénéficiaires avant et après la création de leurs projets', 'ضمان مواكبة المستفيدين قبل وبعد إنشاء مشاريعهم') }}</li>
                    <li>{{ $tr('Fournir des formations visant à renforcer les capacités entrepreneuriales des participants', 'تقديم تكوينات تهدف إلى تعزيز القدرات الريادية للمشاركين') }}</li>
                    <li>{{ $tr('Offrir un environnement propice à l\'accompagnement et à l\'orientation <strong>sans s\'engager à fournir des ressources ou du financement</strong>', 'توفير بيئة ملائمة للمواكبة والتوجيه <strong>دون الالتزام بتقديم موارد أو تمويل</strong>') }}</li>
                </ul>

                <h2 class="text-xl font-bold text-[#1f3864] underline text-center mb-4">{{ $tr('Autorisation de capture et d\'utilisation d\'images', 'تفويض التقاط الصور واستخدامها') }}</h2>
                <p class="text-gray-700 leading-relaxed mb-6">
                    {{ $tr('En signant ce document, j\'autorise l\'association à utiliser mes photos ou enregistrements vidéo à des fins non commerciales.', 'من خلال توقيعي على هذه الوثيقة، أوافق على السماح للجمعية باستخدام صوري أو تسجيلات فيديو لأغراض غير تجارية.') }}
                </p>

                {{-- Checkbox --}}
                <div class="flex items-center justify-center gap-3 p-4 bg-gray-50 rounded-xl mb-6">
                    <input type="checkbox" wire:model="acceptConditions" id="acceptConditions" class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 focus:ring-2" required>
                    <label for="acceptConditions" class="cursor-pointer text-gray-700 text-sm">
                        {{ $tr('J\'accepte toutes les conditions mentionnées ci-dessus', 'أوافق على جميع الشروط المذكورة أعلاه') }}
                    </label>
                </div>
                @error('acceptConditions')
                    <p class="text-red-600 text-sm text-center mb-4">{{ $message }}</p>
                @enderror

                {{-- Project Idea --}}
                <div class="mb-6">
                    <label class="block font-semibold text-gray-900 mb-2">{{ $tr('Idée du projet *', 'فكرة المشروع *') }}</label>
                    <textarea wire:model="projectIdea" rows="4" class="w-full iuhm_textarea rounded-xl border border-gray-200" placeholder="{{ $tr('Décrivez brièvement votre idée de projet', 'صف بإيجاز فكرة مشروعك') }}"></textarea>
                    @error('projectIdea')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- How did you know --}}
                <div class="mb-8">
                    <label class="block font-semibold text-gray-900 mb-2">{{ $tr('Comment avez-vous connu ce projet ? *', 'كيف عرفت بهذا المشروع / المؤسسة ؟ *') }}</label>
                    <textarea wire:model="howKnew" rows="2" class="w-full iuhm_textarea rounded-xl border border-gray-200" placeholder="{{ $tr('Réseaux sociaux, amis...', 'شبكات التواصل الاجتماعي، الأصدقاء...') }}"></textarea>
                    @error('howKnew')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('user.project.detail', $projectId) }}"
                       class="btn-sm rounded-xl font-semibold px-6 py-3 text-center transition-colors border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                        {{ $tr('Retour', 'رجوع') }}
                    </a>
                    <button wire:click="agreeAndContinue"
                            class="btn-sm rounded-xl font-bold px-6 py-3 text-white transition-colors"
                            style="background: #648454;">
                        {{ $tr('Accepter et continuer', 'أوافق وأتابع') }}
                    </button>
                </div>

                {{-- Footer Logos --}}
                <div class="flex flex-wrap items-center justify-center gap-4 mt-8 pt-6 border-t border-gray-100">
                    @if($project->logo1)
                        <img src="{{ asset('uploads/' . $project->logo1) }}" alt="Logo 1" class="h-12 object-contain">
                    @endif
                    @if($project->logo2)
                        <img src="{{ asset('uploads/' . $project->logo2) }}" alt="Logo 2" class="h-12 object-contain">
                    @endif
                    @if($project->logo3)
                        <img src="{{ asset('uploads/' . $project->logo3) }}" alt="Logo 3" class="h-12 object-contain">
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>