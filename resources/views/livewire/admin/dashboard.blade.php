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
				<a href="#" class="text-green-600">Voir tout</a>
			</div>

			<div class="space-y-3">
				<div class="flex items-center justify-between bg-[#FBF8FD]  p-4 rounded-lg ">
					<div>
						<div class="font-semibold">Nouveau projet soumis</div>
						<div class="text-sm text-gray-500">Rénovation Parc Al Amal</div>
					</div>
					<div class="text-sm text-gray-400">Il y a 2h</div>
				</div>

				<div class="flex items-center justify-between p-4 bg-[#FBF8FD] rounded-lg ">
					<div>
						<div class="font-semibold">Nouvel utilisateur admin</div>
						<div class="text-sm text-gray-500">Fatima Zahra - Coordination</div>
					</div>
					<div class="text-sm text-gray-400">Il y a 5h</div>
				</div>

				<div class="text-sm text-gray-400">Plus d'éléments d'activité ici...</div>
			</div>
		</div>

		<div class="bg-[#EAE7EB] content-center h-[200px] p-5 rounded-lg">
			<h4 class="text-[16px] text-[#04103A] font-bold">Support Technique</h4>
			<p class="text-sm text-gray-500 mt-2 font-semibold">Besoin d'aide avec l'ERP? Contactez l'IT.</p>
			<a href="#" class="mt-4 inline-block w-full border-2 border-[#C6C5D0] text-[#04103A] font-bold  text-center hover:bg-[#04103A] transition hover:text-white  px-4 py-2 rounded-[50px]">Ouvrir un Ticket</a>
		</div>
	</div>
</div>
