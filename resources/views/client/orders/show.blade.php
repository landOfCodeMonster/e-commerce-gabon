<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Commande #{{ $order->reference }}
            </h2>
            <a href="{{ route('client.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                &larr; Retour aux commandes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @livewire('client.order-tracker', ['order' => $order])
            @livewire('client.order-detail', ['order' => $order])
        </div>
    </div>
</x-app-layout>
