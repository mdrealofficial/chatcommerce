<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('orders.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Order #{{ $order->id }}
                </h2>
            </div>
            <div>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'processing' => 'bg-purple-100 text-purple-800',
                        'shipped' => 'bg-indigo-100 text-indigo-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="w-20 h-20 object-cover rounded">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">৳{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">৳{{ number_format($item->subtotal, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Order Summary -->
                                <div class="pt-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="text-gray-900">৳{{ number_format($order->items->sum('subtotal'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2">
                                        <span class="text-gray-900">Total:</span>
                                        <span class="text-indigo-600">৳{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Customer Name:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Phone Number:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer->phone ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Delivery Address:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer->address ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Order Date:</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('M d, Y g:i A') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if($order->paymentTransaction)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-600">Transaction ID:</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $order->paymentTransaction->transaction_id }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-600">Payment Status:</dt>
                                        <dd class="mt-1">
                                            @if($order->paymentTransaction->status === 'completed')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            @elseif($order->paymentTransaction->status === 'pending')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Failed
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-600">Amount:</dt>
                                        <dd class="mt-1 text-sm text-gray-900">৳{{ number_format($order->paymentTransaction->amount, 2) }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Update Status -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
                            <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                                        <select name="status" id="status" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Update & Notify Customer
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 p-3 bg-blue-50 rounded-md">
                                <p class="text-xs text-blue-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Customer will receive a Facebook Messenger notification when you update the status.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('inbox.show', $order->conversation) }}" 
                                   class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-center block">
                                    View Conversation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
