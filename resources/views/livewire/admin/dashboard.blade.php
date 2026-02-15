<div class="space-y-6">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        {{-- Total commandes --}}
        <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-blue-50">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Commandes totales</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ $todayOrders }} aujourd'hui</p>
            </div>
        </div>

        {{-- Revenus du mois --}}
        <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-green-50">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Revenus du mois</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($monthRevenue, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-400">XAF</span></p>
                <p class="mt-1 text-xs text-gray-400">Total: {{ number_format($revenueTotal, 0, ',', ' ') }} XAF</p>
            </div>
        </div>

        {{-- Clients --}}
        <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-purple-50">
                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Clients inscrits</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalClients) }}</p>
            </div>
        </div>

        {{-- En attente --}}
        <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-yellow-50">
                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">En attente</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($pendingOrders) }}</p>
                <p class="mt-1 text-xs text-gray-400">commandes en cours</p>
            </div>
        </div>

        {{-- Benefice total --}}
        <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-emerald-50">
                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Benefice total</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalProfit, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-400">XAF</span></p>
                <p class="mt-1 text-xs text-gray-400">Taux conversion: {{ $conversionRate }}%</p>
            </div>
        </div>

        {{-- Aujourd'hui --}}
        <div class="flex items-start gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Aujourd'hui</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($todayRevenue, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-400">XAF</span></p>
                <p class="mt-1 text-xs text-gray-400">{{ $todayOrders }} commande(s)</p>
            </div>
        </div>
    </div>

    {{-- Charts row --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Revenue chart --}}
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-gray-900">Revenus des 6 derniers mois</h3>
            <div class="space-y-3">
                @foreach ($ordersPerMonth as $month)
                    <div class="flex items-center gap-3">
                        <span class="w-10 text-xs font-medium text-gray-500">{{ $month['month'] }}</span>
                        <div class="flex-1">
                            <div class="h-7 overflow-hidden rounded-md bg-gray-100">
                                <div class="flex h-full items-center rounded-md bg-indigo-500 px-2 text-xs font-medium text-white transition-all duration-500"
                                     style="width: {{ $maxMonthRevenue > 0 ? max(($month['total'] / $maxMonthRevenue) * 100, 0) : 0 }}%">
                                    @if ($month['total'] > 0)
                                        {{ number_format($month['total'], 0, ',', ' ') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Status distribution --}}
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-gray-900">Repartition par statut</h3>
            <div class="space-y-4">
                @foreach ($ordersByStatus as $item)
                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-700">
                                <span class="inline-block h-2.5 w-2.5 rounded-full {{ str_replace(['text-yellow-800', 'text-blue-800', 'text-indigo-800', 'text-purple-800', 'text-orange-800', 'text-green-800'], ['bg-yellow-500', 'bg-blue-500', 'bg-indigo-500', 'bg-purple-500', 'bg-orange-500', 'bg-green-500'], $item['status']->color()) }}"></span>
                                {{ $item['status']->label() }}
                            </span>
                            <span class="text-xs font-semibold text-gray-900">{{ $item['count'] }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-gray-100">
                            @php
                                $colorClass = match($item['status']) {
                                    \App\Enums\OrderStatus::Commandee => 'bg-yellow-500',
                                    \App\Enums\OrderStatus::Payee => 'bg-blue-500',
                                    \App\Enums\OrderStatus::Achetee => 'bg-indigo-500',
                                    \App\Enums\OrderStatus::Expediee => 'bg-purple-500',
                                    \App\Enums\OrderStatus::EnLivraison => 'bg-orange-500',
                                    \App\Enums\OrderStatus::Livree => 'bg-green-500',
                                    default => 'bg-gray-400',
                                };
                            @endphp
                            <div class="h-full rounded-full {{ $colorClass }} transition-all duration-500"
                                 style="width: {{ $maxStatusCount > 0 ? max(($item['count'] / $maxStatusCount) * 100, 0) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent orders --}}
    <div class="rounded-xl border border-gray-100 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-900">Dernieres commandes</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Voir tout &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            @if ($recentOrders->isEmpty())
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Aucune commande recente</p>
                </div>
            @else
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-left">
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Reference</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Client</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Statut</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Total</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Date</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recentOrders as $order)
                            <tr class="hover:bg-gray-50/50">
                                <td class="whitespace-nowrap px-6 py-3">
                                    <span class="font-mono text-xs font-semibold text-gray-900">{{ $order->reference }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-xs font-medium text-gray-600">
                                            {{ strtoupper(substr($order->user?->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-gray-700">{{ $order->user?->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-3">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $order->status?->color() ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $order->status?->label() ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-3 font-medium text-gray-900">
                                    {{ number_format($order->total ?? 0, 0, ',', ' ') }} XAF
                                </td>
                                <td class="whitespace-nowrap px-6 py-3 text-gray-500">
                                    {{ optional($order->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                        Voir
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
