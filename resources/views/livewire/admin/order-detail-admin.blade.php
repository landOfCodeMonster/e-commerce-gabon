<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- En-tete commande --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Commande {{ $order->reference }}</h3>
            <p class="text-sm text-gray-500">Par {{ $order->user?->name }} - {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <span class="inline-flex items-center self-start rounded-full px-3 py-1 text-sm font-medium {{ $order->status->color() }}">
            {{ $order->status->label() }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Colonne principale --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Articles --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Articles</h4>
                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <div class="flex gap-4 rounded-lg border p-3">
                            @if ($item->scraped_image)
                                <img src="{{ $item->scraped_image }}" alt="" class="h-16 w-16 rounded object-cover">
                            @else
                                <div class="flex h-16 w-16 items-center justify-center rounded bg-gray-100 text-gray-400 text-xs">N/A</div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item->scraped_title ?? 'Article' }}</p>
                                <p class="text-xs text-gray-500">{{ $item->source_site }}</p>
                                <div class="mt-1 flex flex-wrap gap-2 text-xs text-gray-600">
                                    <span>Quantite: {{ $item->quantity }}</span>
                                    @if ($item->color) <span>Couleur: {{ $item->color }}</span> @endif
                                    @if ($item->size) <span>Taille: {{ $item->size }}</span> @endif
                                </div>
                                <div class="mt-1 flex items-center gap-3 text-sm">
                                    <span class="text-gray-500">Prix source: {{ number_format($item->scraped_price, 2) }} {{ $item->scraped_currency }}</span>
                                    <span class="font-medium text-gray-900">{{ number_format($item->converted_price, 0, ',', ' ') }} XAF</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Paiements --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Paiements</h4>
                @if ($order->payments->isEmpty())
                    <p class="text-sm text-gray-500">Aucun paiement enregistre.</p>
                @else
                    <div class="space-y-2">
                        @foreach ($order->payments as $payment)
                            <div class="flex items-center justify-between rounded border p-3">
                                <div>
                                    <span class="text-sm font-medium">{{ ucfirst($payment->payment_method) }}</span>
                                    <span class="ml-2 text-xs text-gray-500 font-mono">{{ Str::limit($payment->transaction_id, 20) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</span>
                                    @if ($payment->status === 'completed')
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-800">Complete</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs text-yellow-800">{{ $payment->status }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Historique des statuts --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Historique des statuts</h4>
                @if ($order->statusHistories->isEmpty())
                    <p class="text-sm text-gray-500">Aucun historique.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($order->statusHistories->sortByDesc('created_at') as $history)
                            <div class="flex items-start gap-3 border-l-2 border-indigo-200 pl-4">
                                <div class="flex-1">
                                    <p class="text-sm">
                                        <span class="font-medium">{{ $history->old_status ? \App\Enums\OrderStatus::from($history->old_status)->label() : '-' }}</span>
                                        <span class="text-gray-400 mx-1">&rarr;</span>
                                        <span class="font-medium">{{ \App\Enums\OrderStatus::from($history->new_status)->label() }}</span>
                                    </p>
                                    @if ($history->comment)
                                        <p class="text-xs text-gray-500 mt-1">{{ $history->comment }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">
                                        Par {{ $history->changedByUser?->name ?? 'Systeme' }} - {{ $history->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Colonne laterale --}}
        <div class="space-y-6">
            {{-- Totaux --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Recapitulatif</h4>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Sous-total</dt>
                        <dd class="font-medium">{{ number_format($order->subtotal, 0, ',', ' ') }} XAF</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Frais de service</dt>
                        <dd class="font-medium">{{ number_format($order->service_fee, 0, ',', ' ') }} XAF</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Frais de livraison</dt>
                        <dd class="font-medium">{{ number_format($order->shipping_fee, 0, ',', ' ') }} XAF</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Marge beneficiaire</dt>
                        <dd class="font-medium">{{ number_format($order->profit_margin, 0, ',', ' ') }} XAF</dd>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <dt class="font-semibold text-gray-900">Total</dt>
                        <dd class="font-bold text-gray-900">{{ number_format($order->total, 0, ',', ' ') }} XAF</dd>
                    </div>
                </dl>
            </div>

            {{-- Changement de statut --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Changer le statut</h4>
                @error('status') <p class="mb-2 text-sm text-red-600">{{ $message }}</p> @enderror

                @if (count($this->allowedTransitions) > 0)
                    <div class="space-y-3">
                        <select wire:model="newStatus" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selectionner un statut</option>
                            @foreach ($this->allowedTransitions as $transition)
                                <option value="{{ $transition->value }}">{{ $transition->label() }}</option>
                            @endforeach
                        </select>
                        <textarea wire:model="statusComment" rows="2" placeholder="Commentaire (optionnel)..."
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <button wire:click="updateStatus" class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Mettre a jour
                        </button>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Aucune transition disponible.</p>
                @endif
            </div>

            {{-- Marge beneficiaire --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Marge beneficiaire</h4>
                <div class="flex gap-2">
                    <input wire:model="profitMargin" type="number" step="0.01" min="0"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button wire:click="updateProfitMargin" class="rounded-lg bg-gray-800 px-3 py-2 text-sm text-white hover:bg-gray-900">
                        OK
                    </button>
                </div>
            </div>

            {{-- Frais de livraison --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Frais de livraison</h4>
                <div class="flex gap-2">
                    <input wire:model="shippingFee" type="number" step="0.01" min="0"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button wire:click="updateShippingFee" class="rounded-lg bg-gray-800 px-3 py-2 text-sm text-white hover:bg-gray-900">
                        OK
                    </button>
                </div>
            </div>

            {{-- Notes admin --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Notes administrateur</h4>
                <textarea wire:model="adminNotes" rows="4" placeholder="Notes internes..."
                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <button wire:click="saveAdminNotes" class="mt-2 w-full rounded-lg bg-gray-800 px-4 py-2 text-sm text-white hover:bg-gray-900">
                    Sauvegarder
                </button>
            </div>

            {{-- Info client --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h4 class="mb-4 font-semibold text-gray-900">Client</h4>
                <dl class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Nom</dt>
                        <dd>{{ $order->user?->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email</dt>
                        <dd>{{ $order->user?->email }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Telephone</dt>
                        <dd>{{ $order->user?->phone ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Ville</dt>
                        <dd>{{ $order->user?->city ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Adresse</dt>
                        <dd>{{ $order->user?->address ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
