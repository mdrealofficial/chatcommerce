<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">Products</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your product catalog</p>
            </div>
            <a href="{{ route('products.create') }}" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-wider hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 shadow-lg transform hover:scale-105 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Product
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden border border-gray-100">
                <div class="p-6">
                    <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" placeholder="Search products by name or description..." value="{{ request('search') }}" 
                                    class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition">
                            </div>
                        </div>
                        <div class="w-full md:w-48">
                            <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-gray-300 transition transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                        @if(request('search') || request('status') !== null)
                            <a href="{{ route('products.index') }}" class="inline-flex justify-center items-center px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-200 transition">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-1">
                            <div class="relative group">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($product->is_active)
                                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">Active</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg">Inactive</span>
                                    @endif
                                </div>

                                <!-- Stock Badge -->
                                @if($product->stock_quantity < 5)
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-full shadow-lg">Low Stock</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-5">
                                <h3 class="font-bold text-lg text-gray-900 mb-2 truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
                                
                                @if($product->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                                @endif

                                <div class="flex items-baseline gap-2 mb-3">
                                    @if($product->special_price)
                                        <span class="text-2xl font-bold text-green-600">৳{{ number_format($product->special_price, 2) }}</span>
                                        <span class="text-sm text-gray-400 line-through">৳{{ number_format($product->price, 2) }}</span>
                                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded">SALE</span>
                                    @else
                                        <span class="text-2xl font-bold text-gray-900">৳{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                                    <span class="text-sm text-gray-600">Stock:</span>
                                    <span class="font-bold {{ $product->stock_quantity < 5 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $product->stock_quantity }} units
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product) }}" class="flex-1 text-center px-4 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition transform hover:scale-105">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 transition transform hover:scale-105">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        {{ $products->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-dashed border-gray-300">
                    <div class="p-16 text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-100 to-green-200 rounded-full mb-6">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">No products found</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">Start building your product catalog by adding your first product. It only takes a minute!</p>
                        <a href="{{ route('products.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-xl font-bold text-base text-white uppercase tracking-wider hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 shadow-xl transform hover:scale-105 transition-all duration-200">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
