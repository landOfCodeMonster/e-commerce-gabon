<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Cancel confirmation modal --}}
    @if ($confirmingCancel)
        <div class="rounded-xl border-2 border-red-200 bg-red-50 p-6">
            <h4 class="text-lg font-semibold text-red-800">Annuler cette commande ?</h4>
            <p class="mt-1 text-sm text-red-600">Cette action est irreversible. La commande passera au statut "Annulee".</p>
            <div class="mt-4">
                <label class="block text-sm font-medium text-red-700">Motif d'annulation (optionnel)</label>
                <textarea wire:model="cancelReason" rows="2" class="mt-1 w-full rounded-lg border-red-300 text-sm focus:border-red-500 focus:ring-red-500" placeholder="Raison de l'annulation..."></textarea>
            </div>
            <div class="mt-4 flex gap-3">
                <button wire:click="cancelOrder" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    Confirmer l'annulation
                </button>
                <button wire:click="dismissCancel" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Non, garder la commande
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main column --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Articles --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="font-semibold text-gray-900">Articles commandes</h4>
                    @if ($this->canEdit && !$editing)
                        <button wire:click="startEditing" class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                            Modifier
                        </button>
                    @endif
                </div>

                @error('items') <p class="mb-3 text-sm text-red-600">{{ $message }}</p> @enderror

                @if ($editing)
                    {{-- Edit mode --}}
                    <div class="space-y-3">
                        @foreach ($editItems as $index => $editItem)
                            <div class="flex items-start gap-4 rounded-lg border border-indigo-200 bg-indigo-50/30 p-3">
                                <div class="flex-1 space-y-2">
                                    <p class="text-sm font-medium text-gray-900">{{ $editItem['scraped_title'] }}</p>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <label class="block text-xs text-gray-500">Quantite</label>
                                            <input wire:model="editItems.{{ $index }}.quantity" type="number" min="1" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500">Couleur</label>
                                            <input wire:model="editItems.{{ $index }}.color" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500">Taille</label>
                                            <input wire:model="editItems.{{ $index }}.size" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                        </div>
                                    </div>
                                </div>
                                @if (count($editItems) > 1)
                                    <button wire:click="removeItem({{ $editItem['id'] }})" class="mt-1 rounded p-1 text-red-400 hover:bg-red-50 hover:text-red-600" title="Supprimer">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button wire:click="saveChanges" class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            Enregistrer
                        </button>
                        <button wire:click="cancelEditing" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                    </div>
                @else
                    {{-- View mode --}}
                    <div class="space-y-3">
                        @foreach ($order->items as $item)
                            <div class="flex gap-4 rounded-lg border p-3">
                                @if ($item->scraped_image)
                                    <img src="{{ $item->scraped_image }}" alt="" class="h-16 w-16 rounded-lg object-cover">
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-gray-100 text-xs text-gray-400">N/A</div>
                                @endif
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $item->scraped_title ?? 'Article' }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->source_site }}</p>
                                    <div class="mt-1 flex flex-wrap gap-2 text-xs text-gray-600">
                                        <span>Qte: {{ $item->quantity }}</span>
                                        @if ($item->color) <span>| {{ $item->color }}</span> @endif
                                        @if ($item->size) <span>| {{ $item->size }}</span> @endif
                                    </div>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-xs text-gray-400 line-through">{{ number_format($item->scraped_price, 2) }} {{ $item->scraped_currency }}</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ number_format($item->converted_price, 0, ',', ' ') }} XAF</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Payment section --}}
            @if ($order->status === \App\Enums\OrderStatus::Commandee && !$editing)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="mb-4 font-semibold text-gray-900">Paiement</h4>
                    @livewire('client.payment-form', ['order' => $order])
                </div>
            @endif

            {{-- Status history --}}
            @if ($order->statusHistories->isNotEmpty())
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="mb-4 font-semibold text-gray-900">Historique</h4>
                    <div class="space-y-3">
                        @foreach ($order->statusHistories->sortByDesc('created_at') as $history)
                            <div class="flex items-start gap-3 border-l-2 border-indigo-200 pl-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ \App\Enums\OrderStatus::from($history->new_status)->label() }}
                                    </p>
                                    @if ($history->comment)
                                        <p class="text-xs text-gray-500">{{ $history->comment }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">{{ $history->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Summary --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h4 class="mb-4 font-semibold text-gray-900">Recapitulatif</h4>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Sous-total articles</dt>
                        <dd class="font-medium">{{ number_format($order->subtotal, 0, ',', ' ') }} XAF</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Frais de service</dt>
                        <dd class="font-medium">{{ number_format($order->service_fee_total, 0, ',', ' ') }} XAF</dd>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <dt class="font-semibold text-gray-900">Total</dt>
                        <dd class="font-bold text-gray-900">{{ number_format($order->total, 0, ',', ' ') }} XAF</dd>
                    </div>
                </dl>
            </div>

            {{-- Status --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h4 class="mb-2 font-semibold text-gray-900">Statut actuel</h4>
                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $order->status->color() }}">
                    {{ $order->status->label() }}
                </span>
            </div>

            {{-- Actions --}}
            @if ($this->canCancel && !$confirmingCancel)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="mb-3 font-semibold text-gray-900">Actions</h4>
                    <button wire:click="confirmCancel" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border-2 border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        Annuler la commande
                    </button>
                </div>
            @endif

            {{-- Invoice --}}
            @if ($order->invoice)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="mb-2 font-semibold text-gray-900">Facture</h4>
                    <a href="{{ route('client.invoices.download', $order->invoice) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Telecharger
                    </a>
                </div>
            @endif

            @if ($order->notes)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h4 class="mb-2 font-semibold text-gray-900">Notes</h4>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
