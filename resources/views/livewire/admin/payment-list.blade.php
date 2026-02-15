<div class="space-y-4">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par reference commande..."
            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs">
        <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Tous les statuts</option>
            <option value="pending">En attente</option>
            <option value="completed">Complete</option>
            <option value="failed">Echoue</option>
            <option value="refunded">Rembourse</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Commande</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Methode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Transaction</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Montant</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3">
                                <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                    {{ $payment->order?->reference ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $payment->user?->name ?? 'N/A' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                                @switch($payment->payment_method)
                                    @case('stripe') <span class="inline-flex items-center rounded bg-purple-100 px-2 py-0.5 text-xs text-purple-800">Stripe</span> @break
                                    @case('paypal') <span class="inline-flex items-center rounded bg-blue-100 px-2 py-0.5 text-xs text-blue-800">PayPal</span> @break
                                    @case('simulation') <span class="inline-flex items-center rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-800">Simulation</span> @break
                                    @default <span class="text-gray-500">{{ $payment->payment_method }}</span>
                                @endswitch
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-gray-500 font-mono">{{ Str::limit($payment->transaction_id, 20) }}</td>
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                @switch($payment->status)
                                    @case('completed')
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Complete</span>
                                        @break
                                    @case('pending')
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">En attente</span>
                                        @break
                                    @case('failed')
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Echoue</span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">{{ $payment->status }}</span>
                                @endswitch
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-500">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">Aucun paiement trouve.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t px-4 py-3">
            {{ $payments->links() }}
        </div>
    </div>
</div>
