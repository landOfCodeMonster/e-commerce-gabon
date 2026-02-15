<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            Commande #{{ $order->reference }}
        </div>
    </x-slot>

    <div class="mb-6">
        <x-order-progress :status="$order->status" />
    </div>

    @livewire('admin.order-detail-admin', ['order' => $order])
</x-admin-layout>
