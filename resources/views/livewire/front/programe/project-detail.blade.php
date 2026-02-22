<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success/Error Messages -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Project Header -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $project->project_name }}</h1>
                    <p class="text-gray-600 mb-4">{{ $project->description }}</p>
                    
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="ri-file-list-line text-blue-600"></i>
                            <span class="text-gray-700">{{ count($formulaires) }} Formulaires</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ri-checkbox-circle-line text-green-600"></i>
                            <span class="text-gray-700">
                                {{ collect($formulaires)->where('is_completed', true)->count() }} Completed
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ri-time-line text-orange-600"></i>
                            <span class="text-gray-700">
                                {{ collect($formulaires)->where('is_completed', false)->count() }} Pending
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                        {{ $project->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $project->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        @php
            $totalForms = count($formulaires);
            $completedForms = collect($formulaires)->where('is_completed', true)->count();
            $progress = $totalForms > 0 ? ($completedForms / $totalForms) * 100 : 0;
        @endphp
        
        @if($totalForms > 0)
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-gray-800">Overall Progress</h2>
                    <span class="text-2xl font-bold text-blue-600">{{ round($progress) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-500"
                         style="width: {{ $progress }}%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    {{ $completedForms }} of {{ $totalForms }} formulaires completed
                </p>
            </div>
        @endif

        <!-- Formulaires Cards Grid -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Required Formulaires</h2>
            
            @if(count($formulaires) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($formulaires as $index => $formulaire)
                        @php
                            $canStart = true;
                            // Check if previous required formulaires are completed
                            for ($i = 0; $i < $index; $i++) {
                                if ($formulaires[$i]['is_required'] && !$formulaires[$i]['is_completed']) {
                                    $canStart = false;
                                    break;
                                }
                            }
                        @endphp
                        
                        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-2 
                            {{ $formulaire['is_completed'] ? 'border-green-500' : ($canStart ? 'border-blue-400' : 'border-gray-300') }}">
                            
                            <!-- Card Header with Icon -->
                            <div class="p-6" style="background: linear-gradient(135deg, {{ $formulaire['color'] }}15 0%, {{ $formulaire['color'] }}05 100%);">
                                <div class="flex items-start justify-between mb-4">
                                    <!-- Order Badge -->
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm
                                        {{ $formulaire['is_completed'] ? 'bg-green-500' : 'bg-blue-500' }}">
                                        {{ $formulaire['order'] }}
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    @if($formulaire['is_completed'])
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="ri-checkbox-circle-fill mr-1"></i> Completed
                                        </span>
                                    @elseif(!$canStart)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            <i class="ri-lock-line mr-1"></i> Locked
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="ri-play-circle-line mr-1"></i> Available
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Icon -->
                                <div class="flex justify-center mb-4">
                                    <div class="w-20 h-20 rounded-full flex items-center justify-center bg-white shadow-md">
                                        <i class="{{ $formulaire['icon'] }} text-4xl" style="color: {{ $formulaire['color'] }};"></i>
                                    </div>
                                </div>

                                <!-- Title -->
                                <h3 class="text-lg font-bold text-gray-900 text-center mb-2">
                                    {{ $formulaire['title'] }}
                                </h3>
                                
                                @if($formulaire['title_ar'])
                                    <p class="text-sm text-gray-600 text-center mb-3" dir="rtl">{{ $formulaire['title_ar'] }}</p>
                                @endif
                            </div>
                            
                            <!-- Card Body -->
                            <div class="p-6">
                                <!-- Badges -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if($formulaire['is_required'])
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <i class="ri-star-fill mr-1"></i> Required
                                        </span>
                                    @endif
                                    
                                    @if($formulaire['has_introduction'])
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="ri-book-open-line mr-1"></i> Introduction
                                        </span>
                                    @endif
                                </div>

                                <!-- Status Info -->
                                @if($formulaire['is_completed'])
                                    <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                                        <div class="flex items-center gap-2 text-sm text-green-700">
                                            <i class="ri-checkbox-circle-fill text-lg"></i>
                                            <div>
                                                <div class="font-semibold">Completed</div>
                                                <div class="text-xs">{{ \Carbon\Carbon::parse($formulaire['submitted_at'])->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(!$canStart)
                                    <div class="mb-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <p class="text-sm text-yellow-800 flex items-start gap-2">
                                            <i class="ri-lock-line mt-0.5"></i>
                                            <span>Complete previous required forms to unlock</span>
                                        </p>
                                    </div>
                                @else
                                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex items-center gap-2 text-sm text-blue-700">
                                            <i class="ri-play-circle-line text-lg"></i>
                                            <span class="font-semibold">Ready to start</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Action Button -->
                                @if($formulaire['is_completed'])
                                    <button wire:click="startFormulaire({{ $index }})"
                                            class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium flex items-center justify-center gap-2">
                                        <i class="ri-eye-line"></i>
                                        View Submission
                                    </button>
                                @else
                                    <button wire:click="startFormulaire({{ $index }})"
                                            {{ !$canStart ? 'disabled' : '' }}
                                            class="w-full px-4 py-3 rounded-lg font-medium transition flex items-center justify-center gap-2
                                                {{ $canStart 
                                                    ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-md hover:shadow-lg' 
                                                    : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                                        <i class="ri-play-circle-line text-lg"></i>
                                        {{ $index === $currentFormulaireIndex ? 'Continue' : 'Start Form' }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <i class="ri-file-list-line text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Formulaires Available</h3>
                    <p class="text-gray-500">This project doesn't have any formulaires attached yet.</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
       
    </div>
</div>
