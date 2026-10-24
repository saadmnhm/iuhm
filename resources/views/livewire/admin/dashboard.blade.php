<div class="p-6 font-sans">
	<div class="bg-gradient-to-r from-[#0f2441] to-[#15304a] text-white p-9 rounded-xl h-[248px] shadow-lg flex items-center justify-between">
		<div>
			<h1 class="text-3xl text-[48px] font-bold">Bienvenue, {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</h1>
			<p class=" text-[18px] opacity-90 text-gray-200 mt-1">Association Initiative Urbaine - ERP</p>
			<a href="#" class="inline-block mt-3 bg-white font-bold text-[#12345a] px-3 py-1 rounded-md text-sm"><i class="ri-function-line mr-2 text-[20px] font-[500] text-[#066E1B] relative top-[1px] "></i>Vue d'ensemble du système</a>
		</div>
		<div class="w-64 h-20 rounded-md bg-gradient-to-br from-[#12345a] to-[#0b2340] opacity-95"></div>
	</div>

	<h3 class="text-slate-900 text-lg font-semibold mt-8 mb-3">Apps pour vous</h3>
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
		@foreach($menu as $item)
		<div class="bg-[#F5F3F7] rounded-lg p-6 min-h-[268px] shadow-sm content-center ">
			<div class="text-2xl mb-3 h-[50px] w-[50px] flex items-center justify-center text-[#0f2441] rounded-[10px] bg-white">
				<i class="{{ $item['icon'] }}"></i>
			</div>
			<h3 class="text-[20px] font-bold text-[#04103A]">{{ $item['label'] }}</h3>
			<small class="block mt-3 mb-3 text-[14px] font-[400] tracking-[2px] text-gray-400 uppercase">ADMINISTRATIVE</small>
			<a href="{{ $item['route'] }}" class="inline-block text-[#066E1B] font-bold  hover:scale-105">Accéder<i class="ri-arrow-right-long-line relative top-[1px] left-[2px]"></i></a>
		</div>
		@endforeach

	</div>

	<div class="grid  grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
		<div class="lg:col-span-2 bg-[#F5F3F7]  p-5 rounded-lg shadow-sm">
			<div class="flex items-center justify-between mb-4">
				<h4 class="text-lg font-semibold">Activité Récente</h4>
				<a href="{{ route('admin.all.submissions') }}" class="text-green-600">Voir tout</a>
			</div>

			<div class="space-y-3">
				@forelse($recentActivities as $activity)
				<div class="flex items-center justify-between bg-[#FBF8FD]  p-4 rounded-lg ">
					<div class="flex items-center gap-3">
						<div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 text-sm">
							<i class="{{ $activity['icon'] }}"></i>
						</div>
						<div>
							<div class="font-semibold text-[#04103A]">{{ $activity['title'] }}</div>
							<div class="text-sm text-gray-500">{{ $activity['subtitle'] }}</div>
						</div>
					</div>
					<div class="text-sm text-gray-400">{{ $activity['time'] }}</div>
				</div>
				@empty
				<div class="text-sm text-gray-400 text-center py-8">Aucune activité récente.</div>
				@endforelse
			</div>
		</div>

		<div class="bg-[#EAE7EB] content-center h-[200px] p-5 rounded-lg">
			<h4 class="text-[16px] text-[#04103A] font-bold">Support Technique</h4>
			<p class="text-sm text-gray-500 mt-2 font-semibold">Besoin d'aide avec l'ERP? Contactez l'IT.</p>
			<button wire:click="openCreateTicketModal" class="mt-4 inline-block w-full border-2 border-[#C6C5D0] text-[#04103A] font-bold  text-center hover:bg-[#04103A] transition hover:text-white  px-4 py-2 rounded-[50px]">Ouvrir un Ticket</button>
		</div>
	</div>

    {{-- Ticket creation modal (inside Livewire root div) --}}
    @if($showCreateTicketModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" role="dialog" aria-modal="true">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h5 class="text-lg font-bold flex items-center gap-2">
                    <i class="ri-chat-3-line text-blue-600"></i>Ouvrir un ticket pour l'équipe de développement
                </h5>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="createTicket" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sujet du ticket</label>
                        <input type="text" wire:model="ticketSubject" placeholder="ex: Bug sur l'export PDF des formulaires"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                        @error('ticketSubject') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Message / Description</label>
                        <textarea wire:model="ticketMessage" rows="4" placeholder="Décrivez le problème ou la demande de correction..."
                                  class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10"></textarea>
                        @error('ticketMessage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Capture d'écran / Pièce jointe</label>
                        <input type="file" wire:model="ticketImage" accept="image/*"
                               class="w-full block text-sm text-slate-500
                                       @error('ticketImage') text-red-500 @enderror
                                       rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57] focus:ring-4 focus:ring-[#0f1d57]/10">
                        @error('ticketImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP (max 10 Mo)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catégorie</label>
                            <select wire:model="ticketCategory" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57]">
                                <option value="technical">Technique</option>
                                <option value="bug">Bug</option>
                                <option value="general">Général</option>
                                <option value="account">Compte</option>
                                <option value="form">Formulaire</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Priorité</label>
                            <select wire:model="ticketPriority" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57]">
                                <option value="low">Basse</option>
                                <option value="medium">Moyenne</option>
                                <option value="high">Haute</option>
                                <option value="urgent">Urgente</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="closeCreateTicketModal" class="px-4 py-2 text-sm font-semibold text-gray-700 rounded-full hover:bg-gray-200 transition-colors">Annuler</button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white rounded-full bg-[#0f1d57] hover:bg-[#14256f] transition-colors">Créer le Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
