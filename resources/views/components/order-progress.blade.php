@props(['status'])

@php
    $statusEnum = $status instanceof \App\Enums\OrderStatus ? $status : \App\Enums\OrderStatus::from($status);
    $currentStep = $statusEnum->step();
    $steps = [
        ['step' => 1, 'label' => 'Commandee'],
        ['step' => 2, 'label' => 'Payee'],
        ['step' => 3, 'label' => 'Achetee'],
        ['step' => 4, 'label' => 'Expediee'],
        ['step' => 5, 'label' => 'En livraison'],
        ['step' => 6, 'label' => 'Livree'],
    ];
@endphp

@if($statusEnum === \App\Enums\OrderStatus::Annulee)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
        <span class="text-red-600 font-semibold">Commande annulee</span>
    </div>
@else
    <div class="flex items-center justify-between w-full">
        @foreach($steps as $index => $step)
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold
                    {{ $currentStep >= $step['step'] ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                    @if($currentStep > $step['step'])
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @else
                        {{ $step['step'] }}
                    @endif
                </div>
                <span class="text-xs mt-2 text-center {{ $currentStep >= $step['step'] ? 'text-indigo-600 font-medium' : 'text-gray-400' }}">
                    {{ $step['label'] }}
                </span>
            </div>
            @if(!$loop->last)
                <div class="flex-1 h-0.5 mx-2 {{ $currentStep > $step['step'] ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
            @endif
        @endforeach
    </div>
@endif
