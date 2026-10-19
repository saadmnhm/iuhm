@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif class="p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h4 class="font-bold text-xl text-gray-900 mb-0">{{ $tr('Support - Mes tickets', 'الدعم - تذاكري') }}</h4>
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-white transition-colors text-sm" style="background: #648454;">
            <i class="ri-add-line"></i> {{ $tr('Nouveau ticket', 'تذكرة جديدة') }}
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="flex items-center gap-2 rounded-xl px-4 py-3 mb-4 bg-green-50 border border-green-100 text-green-800" role="alert">
            {{ session('success') }}
            <button type="button" class="ml-auto p-1 rounded-lg text-green-400 hover:text-green-600 hover:bg-green-100" wire:click="$dispatch('close-flash')">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>
    @endif

    <!-- Tickets List -->
    <div class="space-y-4">
        @forelse($tickets as $ticket)
        <div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex-1">
                            <h6 class="font-bold text-gray-900 mb-1">{{ $ticket->subject }}</h6>
                            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ Str::limit($ticket->message, 150) }}</p>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->status === 'open' ? 'bg-amber-100 text-amber-800' :
                                        ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' :
                                            ($ticket->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $ticket->status_label }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' :
                                        ($ticket->priority === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ [
                                        'low' => $tr('Basse', 'منخفضة'),
                                        'medium' => $tr('Moyenne', 'متوسطة'),
                                        'high' => $tr('Haute', 'مرتفعة'),
                                        'urgent' => $tr('Urgente', 'عاجلة'),
                                    ][$ticket->priority] ?? ucfirst($ticket->priority) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($ticket->admin_response)
                    <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                        <p class="font-medium text-green-700 text-sm mb-1 flex items-center gap-1">
                            <i class="ri-customer-service-line"></i> {{ $tr("Réponse de l'administration", 'رد الإدارة') }}
                        </p>
                        <p class="text-sm text-gray-700 mb-0">{{ $ticket->admin_response }}</p>
                        @if($ticket->responded_at)
                        <p class="text-xs text-gray-400 mt-2">{{ $ticket->responded_at->diffForHumans() }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <i class="ri-customer-service-2-line text-6xl text-gray-300"></i>
            <p class="text-gray-500 mt-4">{{ $tr("Aucun ticket de support. Créez-en un si vous avez besoin d'aide !", 'لا توجد أي تذاكر دعم. أنشئ واحدة إذا كنت بحاجة للمساعدة!') }}</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>

    <!-- Create Ticket Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" role="dialog" aria-modal="true" aria-labelledby="create-ticket-modal-title">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h5 id="create-ticket-modal-title" class="font-bold text-lg text-gray-900">{{ $tr('Nouveau ticket de support', 'تذكرة دعم جديدة') }}</h5>
                <button type="button" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100" wire:click="$set('showCreateModal', false)">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $tr('Sujet', 'الموضوع') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="subject" 
                        class="w-full iuhm_input rounded-xl border-gray-200 @error('subject') ring-2 ring-red-300 @enderror"
                        placeholder="{{ $tr('Décrivez brièvement votre problème', 'صف مشكلتك باختصار') }}">
                    @error('subject') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $tr('Catégorie', 'الفئة') }}</label>
                        <select wire:model="category" class="w-full iuhm_select rounded-xl border-gray-200">
                            <option value="general">{{ $tr('Général', 'عام') }}</option>
                            <option value="technical">{{ $tr('Technique', 'تقني') }}</option>
                            <option value="account">{{ $tr('Mon compte', 'حسابي') }}</option>
                            <option value="form">{{ $tr('Formulaire', 'استمارة') }}</option>
                            <option value="other">{{ $tr('Autre', 'أخرى') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $tr('Priorité', 'الأولوية') }}</label>
                        <select wire:model="priority" class="w-full iuhm_select rounded-xl border-gray-200">
                            <option value="low">{{ $tr('Basse', 'منخفضة') }}</option>
                            <option value="medium">{{ $tr('Moyenne', 'متوسطة') }}</option>
                            <option value="high">{{ $tr('Haute', 'مرتفعة') }}</option>
                            <option value="urgent">{{ $tr('Urgente', 'عاجلة') }}</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ $tr('Message', 'الرسالة') }} <span class="text-red-500">*</span></label>
                    <textarea wire:model="message" class="w-full iuhm_textarea rounded-xl border-gray-200 @error('message') ring-2 ring-red-300 @enderror" 
                        rows="5" placeholder="{{ $tr('Décrivez votre problème en détail...', 'اشرح مشكلتك بالتفصيل...') }}"></textarea>
                    @error('message') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-xl font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors" wire:click="$set('showCreateModal', false)">{{ $tr('Annuler', 'إلغاء') }}</button>
                <button type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-white transition-colors" style="background: #648454;" wire:click="createTicket">
                    <i class="ri-send-plane-line"></i> {{ $tr('Envoyer', 'إرسال') }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>