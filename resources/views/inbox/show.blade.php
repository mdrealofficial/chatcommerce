<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('inbox.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="flex items-center">
                    <img src="{{ $conversation->customer->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($conversation->customer->name ?? 'Customer') }}" 
                         alt="{{ $conversation->customer->name }}" 
                         class="h-10 w-10 rounded-full">
                    <div class="ml-3">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $conversation->customer->name ?? 'Unknown Customer' }}
                        </h2>
                        @if($conversation->customer->phone)
                            <p class="text-sm text-gray-600">{{ $conversation->customer->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chat Area -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col" style="height: calc(100vh - 200px);">
                        <!-- Messages -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
                            @forelse($messages as $message)
                                <div class="flex {{ $message->sender_type === 'customer' ? 'justify-start' : 'justify-end' }}">
                                    <div class="max-w-xs lg:max-w-md">
                                        @if($message->sender_type === 'customer')
                                            <div class="flex items-start">
                                                <img src="{{ $conversation->customer->profile_pic ?? 'https://ui-avatars.com/api/?name=' . urlencode($conversation->customer->name ?? 'C') }}" 
                                                     class="h-8 w-8 rounded-full mr-2">
                                                <div>
                                                    <div class="bg-gray-100 rounded-lg px-4 py-2">
                                                        <p class="text-sm text-gray-900">{{ $message->message }}</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 ml-1">{{ $message->created_at->format('g:i A') }}</p>
                                                </div>
                                            </div>
                                        @elseif($message->sender_type === 'seller')
                                            <div class="flex items-start justify-end">
                                                <div class="text-right">
                                                    <div class="bg-indigo-600 rounded-lg px-4 py-2">
                                                        <p class="text-sm text-white">{{ $message->message }}</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1 mr-1">{{ $message->created_at->format('g:i A') }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex justify-center">
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2 max-w-sm">
                                                    <p class="text-xs text-yellow-800 text-center">
                                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Bot: {{ $message->message }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <p class="text-gray-500">No messages yet. Start the conversation!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Message Input -->
                        <div class="border-t border-gray-200 p-4 bg-gray-50">
                            <form action="{{ route('inbox.send', $conversation) }}" method="POST" class="flex gap-2">
                                @csrf
                                <textarea name="message" rows="2" required placeholder="Type your message..." 
                                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition h-fit self-end">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- POS Panel - Product List -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="max-height: calc(100vh - 200px);">
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Products (POS)</h3>
                            <p class="text-xs text-gray-600 mt-1">Click to send product to customer</p>
                        </div>
                        
                        <div class="overflow-y-auto p-4 space-y-3" style="max-height: calc(100vh - 280px);">
                            @forelse($products as $product)
                                <form action="{{ route('inbox.send-product', $conversation) }}" method="POST" class="block">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="w-full text-left hover:bg-gray-50 rounded-lg p-3 border border-gray-200 transition">
                                        <div class="flex gap-3">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h4>
                                                <div class="mt-1">
                                                    @if($product->special_price)
                                                        <span class="text-sm font-bold text-indigo-600">৳{{ number_format($product->special_price, 2) }}</span>
                                                        <span class="text-xs text-gray-500 line-through ml-1">৳{{ number_format($product->price, 2) }}</span>
                                                    @else
                                                        <span class="text-sm font-bold text-gray-900">৳{{ number_format($product->price, 2) }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-600 mt-1">Stock: {{ $product->stock_quantity }}</p>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">No products available</p>
                                    <a href="{{ route('products.create') }}" class="mt-2 text-xs text-indigo-600 hover:text-indigo-800">Add products</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Customer Info</h3>
                            <dl class="space-y-2 text-sm">
                                <div>
                                    <dt class="text-gray-600">Name:</dt>
                                    <dd class="font-medium text-gray-900">{{ $conversation->customer->name ?? 'N/A' }}</dd>
                                </div>
                                @if($conversation->customer->phone)
                                    <div>
                                        <dt class="text-gray-600">Phone:</dt>
                                        <dd class="font-medium text-gray-900">{{ $conversation->customer->phone }}</dd>
                                    </div>
                                @endif
                                @if($conversation->customer->address)
                                    <div>
                                        <dt class="text-gray-600">Address:</dt>
                                        <dd class="font-medium text-gray-900">{{ $conversation->customer->address }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-gray-600">First Contact:</dt>
                                    <dd class="font-medium text-gray-900">{{ $conversation->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom of messages
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Auto-refresh messages every 10 seconds
        setInterval(() => {
            // You can implement AJAX polling here for real-time updates
        }, 10000);
    </script>
</x-app-layout>
