<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inbox - Customer Conversations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($conversations->count() > 0)
                        <div class="space-y-1">
                            @foreach($conversations as $conversation)
                                <a href="{{ route('inbox.show', $conversation) }}" class="block hover:bg-gray-50 rounded-lg p-4 transition {{ !$conversation->is_read ? 'bg-blue-50' : '' }}">
                                    <div class="flex items-center">
                                        <img src="{{ $conversation->customer->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($conversation->customer->name ?? 'Customer') }}" 
                                             alt="{{ $conversation->customer->name ?? 'Customer' }}" 
                                             class="h-12 w-12 rounded-full flex-shrink-0">
                                        
                                        <div class="ml-4 flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $conversation->customer->name ?? 'Unknown Customer' }}
                                                </h3>
                                                <span class="text-xs text-gray-500 ml-2 flex-shrink-0">
                                                    {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : '' }}
                                                </span>
                                            </div>
                                            
                                            @if($conversation->latestMessage)
                                                <p class="text-sm text-gray-600 truncate mt-1">
                                                    @if($conversation->latestMessage->sender_type === 'seller')
                                                        <span class="font-medium">You:</span>
                                                    @endif
                                                    {{ Str::limit($conversation->latestMessage->message, 60) }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="ml-4 flex items-center gap-2">
                                            @if(!$conversation->is_read)
                                                <span class="inline-flex items-center justify-center w-2 h-2 bg-blue-600 rounded-full"></span>
                                            @endif
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $conversations->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No conversations yet</h3>
                            <p class="mt-1 text-sm text-gray-500">When customers message your Facebook page, they'll appear here.</p>
                            <div class="mt-6">
                                <a href="{{ route('pages.connect') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Connect Facebook Page
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
