<div class="space-y-4">
    @error('payment') <div class="rounded-lg bg-red-50 p-3 text-sm text-red-700">{{ $message }}</div> @enderror

    <div class="space-y-3">
        <label class="block text-sm font-medium text-gray-700">Methode de paiement</label>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <label class="flex cursor-pointer items-center gap-3 rounded-lg border-2 p-3 transition-colors {{ $paymentMethod === 'stripe' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                <input wire:model="paymentMethod" type="radio" value="stripe" class="text-indigo-600 focus:ring-indigo-500">
                <div>
                    <p class="text-sm font-medium text-gray-900">Carte bancaire</p>
                    <p class="text-xs text-gray-500">Visa, Mastercard via Stripe</p>
                </div>
            </label>
            <label class="flex cursor-pointer items-center gap-3 rounded-lg border-2 p-3 transition-colors {{ $paymentMethod === 'paypal' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                <input wire:model="paymentMethod" type="radio" value="paypal" class="text-indigo-600 focus:ring-indigo-500">
                <div>
                    <p class="text-sm font-medium text-gray-900">PayPal</p>
                    <p class="text-xs text-gray-500">Payer avec votre compte PayPal</p>
                </div>
            </label>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t pt-4">
        <button wire:click="processPayment" wire:loading.attr="disabled" :disabled="$wire.processing"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
            <span wire:loading wire:target="processPayment">
                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </span>
            Payer {{ number_format($order->total, 0, ',', ' ') }} XAF
        </button>

        @if (app()->environment('local'))
            <button wire:click="simulatePayment" class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Simuler le paiement (dev)
            </button>
        @endif
    </div>
</div>
