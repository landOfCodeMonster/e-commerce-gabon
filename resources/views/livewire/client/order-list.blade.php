<div class="space-y-4">
    @if (!$limit)
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par reference..."
                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs">
            <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Articles</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-xs font-semibold text-gray-900">{{ $order->reference }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-600">{{ $order->items->count() }} article(s)</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->status->color() }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ number_format($order->total, 0, ',', ' ') }} XAF</td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <a href="{{ route('client.orders.show', $order) }}" wire:navigate class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">Aucune commande trouvee.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (!$limit && method_exists($orders, 'links'))
            <div class="border-t px-4 py-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
