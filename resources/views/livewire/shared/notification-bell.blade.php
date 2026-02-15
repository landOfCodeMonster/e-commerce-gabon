<div class="relative" wire:poll.30s>
    <button wire:click="toggleDropdown" class="relative p-2 text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($this->unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    @if($showDropdown)
        <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50">
            <div class="flex justify-between items-center p-3 border-b">
                <h3 class="font-semibold text-sm">Notifications</h3>
                @if($this->unreadCount > 0)
                    <button wire:click="markAllAsRead" class="text-xs text-indigo-600 hover:text-indigo-800">
                        Tout marquer comme lu
                    </button>
                @endif
            </div>
            <div class="max-h-64 overflow-y-auto">
                @forelse($this->notifications as $notification)
                    <div class="p-3 border-b hover:bg-gray-50 cursor-pointer {{ $notification->read_at ? 'opacity-60' : '' }}"
                         wire:click="markAsRead('{{ $notification->id }}')">
                        <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? 'Notification' }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-gray-400">
                        Aucune notification
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
