<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- URL input + scrape --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Ajouter un article</h3>

        <div class="space-y-4">
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700">Lien du produit</label>
                <div class="mt-1 flex gap-2">
                    <input wire:model="url" type="url" id="url" placeholder="https://www.amazon.fr/dp/..."
                        class="flex-1 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button wire:click="scrapeUrl" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                        <span wire:loading wire:target="scrapeUrl">
                            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="scrapeUrl">Analyser</span>
                        <span wire:loading wire:target="scrapeUrl">Analyse...</span>
                    </button>
                </div>
                @error('url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Scraped product preview --}}
            @if ($scrapeSuccess || $manualEntry)
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex gap-4">
                        @if ($scrapedImage)
                            <img src="{{ $scrapedImage }}" alt="" class="h-20 w-20 rounded-lg object-cover">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-gray-200 text-xs text-gray-400">Pas d'image</div>
                        @endif
                        <div class="flex-1 space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500">Titre du produit</label>
                                <input wire:model="scrapedTitle" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm" placeholder="Nom du produit">
                                @error('scrapedTitle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Prix</label>
                                    <input wire:model="scrapedPrice" type="number" step="0.01" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                    @error('scrapedPrice') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Quantite</label>
                                    <input wire:model="quantity" type="number" min="1" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Couleur</label>
                                    <input wire:model="color" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm" placeholder="Optionnel">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Taille</label>
                                    <input wire:model="size" type="text" class="mt-1 w-full rounded-lg border-gray-300 text-sm" placeholder="Optionnel">
                                </div>
                            </div>
                            <button wire:click="addItem" class="inline-flex items-center gap-1 rounded-lg bg-gray-800 px-3 py-1.5 text-xs font-medium text-white hover:bg-gray-900">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Cart items --}}
    @if (count($items) > 0)
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Panier ({{ count($items) }} article(s))</h3>

            <div class="space-y-3">
                @foreach ($items as $index => $item)
                    <div class="flex items-center gap-4 rounded-lg border p-3">
                        @if ($item['scraped_image'])
                            <img src="{{ $item['scraped_image'] }}" alt="" class="h-14 w-14 rounded object-cover">
                        @else
                            <div class="flex h-14 w-14 items-center justify-center rounded bg-gray-100 text-xs text-gray-400">N/A</div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $item['scraped_title'] }}</p>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-gray-400 line-through">{{ number_format($item['scraped_price'], 2) }} {{ $item['scraped_currency'] }}</span>
                                <span class="font-semibold text-gray-900">{{ number_format($item['converted_price'] ?? 0, 0, ',', ' ') }} XAF</span>
                                <span class="text-gray-500">x {{ $item['quantity'] }}</span>
                                @if ($item['color']) <span class="text-gray-400">| {{ $item['color'] }}</span> @endif
                                @if ($item['size']) <span class="text-gray-400">| {{ $item['size'] }}</span> @endif
                            </div>
                        </div>
                        <button wire:click="removeItem({{ $index }})" class="rounded p-1 text-red-400 hover:bg-red-50 hover:text-red-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                        </button>
                    </div>
                @endforeach
            </div>

            @error('items') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="mt-4 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                    <textarea wire:model="notes" rows="2" class="mt-1 w-full rounded-lg border-gray-300 text-sm" placeholder="Instructions speciales..."></textarea>
                </div>

                <div class="flex items-center justify-between border-t pt-3">
                    <div class="text-sm">
                        <div class="text-gray-500">
                            Sous-total : <span class="font-medium text-gray-700">{{ number_format($this->subtotal, 0, ',', ' ') }} XAF</span>
                        </div>
                        <div class="text-gray-500">
                            Frais de service ({{ $this->serviceFeePercent }}%) : <span class="font-medium text-gray-700">{{ number_format($this->serviceFee, 0, ',', ' ') }} XAF</span>
                        </div>
                        <div class="mt-1 text-gray-900">
                            Total estime : <span class="font-bold">{{ number_format($this->estimatedTotal, 0, ',', ' ') }} XAF</span>
                        </div>
                    </div>
                    <button wire:click="submitOrder" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50">
                        <span wire:loading wire:target="submitOrder">
                            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        Valider la commande
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
