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
        @foreach($formStatus as $type => $data)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">{{ $data['info']['label'] }}</p>
                    <p class="text-2xl font-bold mt-1
                        @if($data['has_submission']) text-green-600
                        @else text-gray-400
                        @endif">
                        {{ $data['has_submission'] ? '✓' : '✗' }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center
                    @if($data['info']['color'] == 'blue') bg-blue-100 text-blue-600
                    @elseif($data['info']['color'] == 'green') bg-green-100 text-green-600
                    @elseif($data['info']['color'] == 'purple') bg-purple-100 text-purple-600
                    @elseif($data['info']['color'] == 'yellow') bg-yellow-100 text-yellow-600
                    @elseif($data['info']['color'] == 'pink') bg-pink-100 text-pink-600
                    @endif">
                    <i class="{{ $data['info']['icon'] }} text-2xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Forms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($formStatus as $type => $data)
        <div class="bg-white rounded-xl shadow-sm border-2 
            @if($data['has_submission']) border-{{ $data['info']['color'] }}-200
            @else border-gray-200
            @endif
            overflow-hidden hover:shadow-lg transition-all">
            
            <!-- Card Header -->
            <div class="p-6 bg-gradient-to-br">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 rounded-xl 
                        @if($data['info']['color'] == 'blue') bg-blue-500
                        @elseif($data['info']['color'] == 'green') bg-green-500
                        @elseif($data['info']['color'] == 'purple') bg-purple-500
                        @elseif($data['info']['color'] == 'yellow') bg-yellow-500
                        @elseif($data['info']['color'] == 'pink') bg-pink-500
                        @else bg-gray-500
                        @endif
                        flex items-center justify-center text-white text-2xl shadow-lg">
                        <i class="{{ $data['info']['icon'] }}"></i>
                    </div>
                    
                    @if($data['has_submission'])
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($data['submission']->status == 'draft') bg-gray-100 text-gray-800
                            @elseif($data['submission']->status == 'submitted') bg-blue-100 text-blue-800
                            @elseif($data['submission']->status == 'in_review') bg-yellow-100 text-yellow-800
                            @elseif($data['submission']->status == 'approved') bg-green-100 text-green-800
                            @elseif($data['submission']->status == 'rejected') bg-red-100 text-red-800
                            @endif">
                            {{ $data['submission']->status_label }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                            Non soumis
                        </span>
                    @endif
                </div>
                
                <h3 class="text-xl font-bold text-gray-900">{{ $data['info']['label'] }}</h3>
            </div>

            <!-- Card Body -->
            <div class="p-6 pt-0 ">
                @if($data['has_submission'])
                    @php $submission = $data['submission']; @endphp
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">ID:</span>
                            <span class="font-medium text-gray-900">#{{ $submission->id }}</span>
                        </div>
                        

                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Créé le:</span>
                            <span class="font-medium text-gray-900">{{ $submission->created_at->format('d/m/Y') }}</span>
                        </div>
                        
                        @if($submission->submitted_at)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Soumis le:</span>
                            <span class="font-medium text-gray-900">{{ $submission->submitted_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                        
                        
                    </div>

                    <!-- Action Button -->
                    <div class="mt-4">
                        @if($type == 'business_plan')
                            <a href="{{ route('admin.projects.show', $submission->id) }}" 
                               class="block w-full text-center px-4 py-3 bg-{{ $data['info']['color'] }}-600 hover:bg-{{ $data['info']['color'] }}-700 text-white font-semibold rounded-lg transition">
                                <i class="ri-eye-line mr-2"></i> Voir les détails
                            </a>
                        @else
                            <a href="{{ route('admin.form-submissions.view', ['type' => $type, 'id' => $submission->id]) }}" 
                               class="block w-full text-center px-4 py-3 bg-{{ $data['info']['color'] }}-600 hover:bg-{{ $data['info']['color'] }}-700 text-white font-semibold rounded-lg transition">
                                <i class="ri-eye-line mr-2"></i> Voir les détails
                            </a>
                        @endif
                    </div>
                @else
                    <!-- Not Submitted State -->
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="ri-file-add-line text-3xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-600 font-medium mb-2">Pas encore soumis</p>
                        <p class="text-xs text-gray-500">Ce candidat n'a pas encore rempli ce formulaire</p>
                    </div>
                    
                    <button disabled 
                            class="w-full px-4 py-3 bg-gray-100 text-gray-400 font-semibold rounded-lg cursor-not-allowed">
                        <i class="ri-close-circle-line mr-2"></i> Non disponible
                    </button>
                @endif
            </div>

            <!-- Card Footer -->
            @if($data['has_submission'] && $data['submission']->review_notes)
            <div class="px-6 py-4 bg-yellow-50 border-t border-yellow-100">
                <p class="text-xs font-semibold text-yellow-800 mb-1">
                    <i class="ri-file-text-line"></i> Notes de révision
                </p>
                <p class="text-xs text-yellow-700">{{ Str::limit($data['submission']->review_notes, 80) }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
