@props(['status'])

@php
    $statusEnum = $status instanceof \App\Enums\OrderStatus ? $status : \App\Enums\OrderStatus::from($status);
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusEnum->color() }}">
    {{ $statusEnum->label() }}
</span>
