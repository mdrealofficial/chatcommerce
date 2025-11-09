<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products
            </h2>
            <a href="{{ route('products.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Search products..." 
                                   value="{{ request('search') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div class="w-full sm:w-48">
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-md font-semibold">
                            Search
                        </button>
                        @if(request('search') || request('status') !== null)
                            <a href="{{ route('products.index') }}" 
                               class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition shadow-md font-semibold">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition">
                                    <!-- Product Image -->
                                    <div class="relative bg-gray-100 h-48">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Status Badge -->
                                        <div class="absolute top-2 right-2">
                                            @if($product->is_active)
                                                <span class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded">Active</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded">Inactive</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 mb-2 truncate" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </h3>
                                        
                                        @if($product->description)
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                                {{ Str::limit($product->description, 60) }}
                                            </p>
                                        @endif

                                        <!-- Price -->
                                        <div class="mb-3">
                                            @if($product->special_price)
                                                <div class="flex items-baseline gap-2">
                                                    <span class="text-lg font-bold text-green-600">৳{{ number_format($product->special_price, 2) }}</span>
                                                    <span class="text-sm text-gray-400 line-through">৳{{ number_format($product->price, 2) }}</span>
                                                </div>
                                            @else
                                                <span class="text-lg font-bold text-gray-900">৳{{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>

                                        <!-- Stock -->
                                        <div class="text-sm text-gray-600 mb-4 pb-4 border-b">
                                            Stock: 
                                            <span class="font-semibold {{ $product->stock_quantity < 5 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex gap-2">
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="flex-1 text-center px-3 py-2 bg-orange-500 text-white text-sm font-semibold rounded hover:bg-orange-600 transition shadow-md">
                                                Edit
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" 
                                                  method="POST" 
                                                  class="flex-1" 
                                                  onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full px-3 py-2 bg-red-600 text-white text-sm font-semibold rounded hover:bg-red-700 transition shadow-md">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            {{ $products->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-600 mb-6">Get started by creating your first product.</p>
                        <a href="{{ route('products.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Your First Product
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
