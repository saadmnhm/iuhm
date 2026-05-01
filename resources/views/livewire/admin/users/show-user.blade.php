<div class="p-6 bg-white rounded-lg shadow">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-[#04103A]">{{ $user->name ?? ($user->nom ?? 'Utilisateur') }}</h2>
            <p class="text-sm text-gray-600">{{ $user->email ?? '' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="toggleStatus" class="px-4 py-2 rounded-full text-sm font-medium text-white bg-{{ $user->is_active ? 'green-600' : 'gray-500' }}">
                {{ $user->is_active ? 'Active' : 'Deactivated' }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:underline">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-2">
            <div class="text-xs text-gray-500">Nom complet</div>
            <div class="font-medium text-gray-800">{{ $user->name ?? ($user->nom ?? '-') }}</div>

            <div class="text-xs text-gray-500 mt-4">Prénom</div>
            <div class="font-medium text-gray-800">{{ $user->first_name ?? $user->prenom ?? '-' }}</div>

            <div class="text-xs text-gray-500 mt-4">E-mail</div>
            <div class="font-medium text-gray-800">{{ $user->email ?? '-' }}</div>
        </div>

        <div class="space-y-2">
            <div class="text-xs text-gray-500">Téléphone</div>
            <div class="font-medium text-gray-800">{{ $user->phone ?? '-' }}</div>

            <div class="text-xs text-gray-500 mt-4">Rôle</div>
            <div class="font-medium text-gray-800">{{ $user->role ?? ($user->type ?? '-') }}</div>

            <div class="text-xs text-gray-500 mt-4">Créé le</div>
            <div class="font-medium text-gray-800">{{ optional($user->created_at)->format('d/m/Y H:i') ?? '-' }}</div>
        </div>
    </div>
</div>
