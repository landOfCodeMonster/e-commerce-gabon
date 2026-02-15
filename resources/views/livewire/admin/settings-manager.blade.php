<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        @foreach ($groupedSettings as $group => $items)
            <div class="rounded-lg bg-white p-6 shadow">
                <h3 class="mb-4 text-lg font-semibold capitalize text-gray-900">{{ $group }}</h3>
                <div class="space-y-4">
                    @foreach ($items as $setting)
                        <div>
                            <label for="setting-{{ $setting->key }}" class="block text-sm font-medium text-gray-700">
                                {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            <div class="mt-1">
                                @if ($setting->type === 'boolean')
                                    <select wire:model="settings.{{ $setting->key }}" id="setting-{{ $setting->key }}"
                                        class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="1">Oui</option>
                                        <option value="0">Non</option>
                                    </select>
                                @elseif ($setting->type === 'number')
                                    <input wire:model="settings.{{ $setting->key }}" type="number" step="0.01" id="setting-{{ $setting->key }}"
                                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs">
                                @else
                                    <input wire:model="settings.{{ $setting->key }}" type="text" id="setting-{{ $setting->key }}"
                                        class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Sauvegarder les parametres
            </button>
        </div>
    </form>
</div>
