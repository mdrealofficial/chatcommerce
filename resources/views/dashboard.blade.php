<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Products -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Products</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                                <p class="text-xs text-gray-500">Pending: {{ $pendingOrders }} | Confirmed: {{ $confirmedOrders }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Sales -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Today's Sales</p>
                                <p class="text-2xl font-bold text-gray-900">৳{{ number_format($todaySales, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 {{ $pageConnected ? 'bg-green-500' : 'bg-red-500' }} rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Facebook Page</p>
                                <p class="text-lg font-bold {{ $pageConnected ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $pageConnected ? 'Connected' : 'Not Connected' }}
                                </p>
                                @if (!$pageConnected)
                                    <a href="{{ route('pages.connect') }}" class="text-xs text-blue-600 hover:underline">Connect Now</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Chats -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Chats</h3>
                        <a href="{{ route('inbox.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                    </div>

                    @if ($recentChats->count() > 0)
                        <div class="space-y-4">
                            @foreach ($recentChats as $chat)
                                <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition">
                                    <img src="{{ $chat->customer->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($chat->customer->name) }}" 
                                         alt="{{ $chat->customer->name }}" 
                                         class="h-12 w-12 rounded-full">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $chat->customer->name ?? 'Unknown Customer' }}</p>
                                                <p class="text-sm text-gray-500 truncate">
                                                    {{ $chat->latestMessage ? Str::limit($chat->latestMessage->message, 50) : 'No messages' }}
                                                </p>
                                            </div>
                                            <span class="text-xs text-gray-400">
                                                {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('inbox.show', $chat->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No recent chats</p>
                            @if (!$pageConnected)
                                <p class="mt-1 text-xs text-gray-400">Connect your Facebook page to start receiving messages</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
