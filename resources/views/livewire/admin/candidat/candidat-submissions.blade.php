<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Candidat Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                @if($candidat->profile_image)
                    <img class="w-20 h-20 rounded-full object-cover border-4 border-indigo-100" 
                         src="{{ asset('uploads/'.$candidat->profile_image) }}" 
                         alt="{{ $candidat->nom }}">
                @else
                    <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold border-4 border-indigo-200">
                        {{ strtoupper(substr($candidat->nom, 0, 1)) }}{{ strtoupper(substr($candidat->prenom, 0, 1)) }}
                    </div>
                @endif
                
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $candidat->nom }} {{ $candidat->prenom }}</h1>
                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                        <span><i class="ri-mail-line"></i> {{ $candidat->email }}</span>
                        @if($candidat->phone)
                        <span><i class="ri-phone-line"></i> {{ $candidat->phone }}</span>
                        @endif
                        @if($candidat->address)
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($candidat->address == 'Hay Mohamadi') bg-green-100 text-green-800
                            @elseif($candidat->address == 'Ain Sbaa') bg-purple-100 text-purple-800
                            @elseif($candidat->address == 'Roches noires') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            <i class="ri-map-pin-line"></i> {{ $candidat->address }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.form-submissions') }}" class="text-indigo-600 hover:text-indigo-900">
                <i class="ri-arrow-left-line"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            @foreach($statistics['form_attahed'] as $formulaire)
                @if($formulaire['is_active'] == 'active')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">{{ $formulaire['title'] }}</p>
                                @if($formulaire['completed'])
                                    <p class="text-sm  mt-2 bg-lime-100 text-lime-800 inline-block px-2  rounded">
                                        Submitted
                                    </p>
                                @else
                                    <p class="text-sm  mt-2 text-red-600 bg-red-100 inline-block px-2  rounded">
                                        Not submitted
                                    </p>
                                @endif
                            </div>
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="color: {{ $formulaire['color'] ?? '#6366f1' }}; background-color: {{ $formulaire['color'] ?? '#6366f1' }}30;">
                                <i class="{{ $formulaire['icon'] ?? 'ri-file-list-3-line' }} text-2xl" ></i>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
    </div>

    <!-- Dynamic Form Submissions Section -->
    @if($dynamicSubs->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="ri-file-list-3-line text-indigo-600"></i>
            Formulaires Dynamiques
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($statistics['form_attahed'] as $sub)
                <div class="bg-white rounded-xl shadow-sm border-2 overflow-hidden hover:shadow-lg transition-all"
                    style="border-color: {{ $sub['color'] ?? '#6366f1' }}30;">
                    
                    <!-- Card Header -->
                    <div class="p-5" style="background: linear-gradient(135deg, {{ $sub['color'] ?? '#6366f1' }}10 0%, transparent 100%);">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-xl shadow-md"
                                style="background-color: {{ $sub['color'] ?? '#6366f1' }};">
                                <i class="{{ $sub['icon'] ?? 'ri-file-list-3-line' }}"></i>
                            </div>
                            
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($sub['status_label'] == 'Not Submitted') bg-yellow-100 text-yellow-800
                                @elseif($sub['status_label'] == 'Submitted') bg-blue-100 text-blue-800
                                @elseif($sub['status_label'] == 'in_review') bg-purple-100 text-purple-800
                                @elseif($sub['status_label'] == 'approved') bg-green-100 text-green-800
                                @elseif($sub['status_label'] == 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-600
                                @endif">
                                {{ $sub['status_label'] }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900">{{ $sub['title'] }}</h3>
                        @if($sub->form->title_ar ?? false)
                            <p class="text-sm text-gray-600" dir="rtl">{{ $sub->form->title_ar }}</p>
                        @endif
                    </div>

                    <!-- Card Body -->
                    @if($sub['status_label'] === 'Not Submitted')
                        <div class="p-6 pt-0 ">
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="ri-file-add-line text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-600 font-medium mb-2">Pas encore soumis</p>
                                <p class="text-xs text-gray-500">Ce candidat n'a pas encore rempli ce formulaire</p>
                            </div>
                        
                            <button disabled="" class="w-full px-4 py-3 bg-gray-100 text-gray-400 font-semibold rounded-lg cursor-not-allowed">
                                <i class="ri-close-circle-line mr-2"></i> Non disponible
                            </button>
                        </div>
                    @else
                    <div class="p-6 pt-0 ">
                        <div class="space-y-2 mb-4  py-8">
                            @if($sub['programe'])
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500"><i class="ri-folder-line mr-1"></i> Projet:</span>
                                <span class="font-medium text-gray-900">{{ $sub['programe']['project_name'] }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">ID:</span>
                                <span class="font-medium text-gray-900">#{{ $sub['id'] }}</span>
                            </div>

                            @if($sub['status'] === 'draft')
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Progression:</span>
                                <span class="font-medium text-gray-900">Étape {{ $sub->current_step }} / {{ $sub->form->steps()->count() }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                @php $totalSteps = $sub->form->steps()->count(); @endphp
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalSteps > 0 ? ($sub->current_step / $totalSteps) * 100 : 0 }}%"></div>
                            </div>
                            @endif

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Créé le:</span>
                                <span class="font-medium text-gray-900">{{ $sub['created_at'] }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Soumis le:</span>
                                <span class="font-medium text-gray-900">{{ $sub['submitted_at'] ?? 'Non soumis' }}</span>
                            </div>

                            
                        </div>

                        <!-- Action Button -->
                         @if($sub['status_label'] === 'Not Submitted')
                            <a href="javascript:void(0)"  disabled  style="background-color: {{ $sub['color'] ?? '#6366f1' }}; cursor: not-allowed;"
                            class="block w-full text-center px-4 py-3 bg-yellow-600 text-white font-semibold rounded-lg transition hover:opacity-90">
                                <i class="ri-edit-line mr-2"></i> Not yet
                            </a>
                        @else
                            <div class="mt-4">
                                <a href="{{ route('admin.formulaires.submission.detail', $sub['id']) }}" 
                                class="block w-full text-center px-4 py-3 text-white font-semibold rounded-lg transition hover:opacity-90"
                                style="background-color: {{ $sub['color'] ?? '#6366f1' }};">
                                    <i class="ri-eye-line mr-2"></i> Voir les détails
                                </a>
                            </div>
                        @endif
                    </div>
                        @endif
                    
                </div>
            @endforeach
        </div>
    </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="ri-file-list-3-line text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Dynamic Form Submissions Found</h3>
            <p class="text-gray-500">The candidat has not submitted any dynamic forms yet.</p>
        </div>
    @endif

</div>
