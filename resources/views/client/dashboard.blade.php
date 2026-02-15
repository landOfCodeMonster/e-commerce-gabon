<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tableau de bord</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Commandes en cours</div>
                    <div class="text-3xl font-bold text-indigo-600 mt-2">
                        {{ auth()->user()->orders()->whereNotIn('status', ['livree', 'annulee'])->count() }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Commandes livrees</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">
                        {{ auth()->user()->orders()->where('status', 'livree')->count() }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Total depense</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">
                        {{ number_format(auth()->user()->payments()->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} XAF
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Dernieres commandes</h3>
                    <a href="{{ route('client.orders.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                        Nouvelle commande
                    </a>
                </div>
                @livewire('client.order-list', ['limit' => 5])
            </div>
        </div>
    </div>
</x-app-layout>
