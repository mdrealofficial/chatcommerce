<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Connect Facebook Page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @if($page && $page->is_connected)
                        <!-- Connected Page Info -->
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Page Connected!</h3>
                            <p class="text-gray-600 mb-6">Your Facebook page is successfully connected</p>
                        </div>

                        <div class="border-t border-b border-gray-200 py-6 my-6">
                            <div class="flex items-center">
                                @if($page->page_profile_image)
                                    <img src="{{ $page->page_profile_image }}" alt="{{ $page->page_name }}" class="w-16 h-16 rounded-full">
                                @endif
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $page->page_name }}</h4>
                                    <p class="text-sm text-gray-600">Page ID: {{ $page->page_id }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Connected: {{ $page->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">What's next?</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Add products in your store</li>
                                            <li>Customers can message your page on Facebook</li>
                                            <li>You'll receive messages in the Inbox</li>
                                            <li>Send product cards directly from the inbox</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('products.index') }}" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Manage Products
                            </a>
                            <a href="{{ route('inbox.index') }}" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                View Inbox
                            </a>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <form action="{{ route('pages.disconnect') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect this page?');">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Disconnect Page
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Connect Page -->
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                                <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Connect Your Facebook Page</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">Link your Facebook page to start selling through Messenger. Your customers can browse products and place orders directly in chat.</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6 mb-8">
                            <h4 class="text-sm font-semibold text-gray-900 mb-4">Required Permissions:</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>pages_messaging:</strong> Send and receive messages</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>pages_show_list:</strong> Access your pages list</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>pages_manage_metadata:</strong> Manage page settings</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span><strong>pages_manage_engagement:</strong> Manage conversations</span>
                                </li>
                            </ul>
                        </div>

                        @php
                            $pageController = app(\App\Http\Controllers\PageController::class);
                            $loginUrl = $pageController->getLoginUrl();
                        @endphp

                        <div class="text-center">
                            <a href="{{ $loginUrl }}" class="inline-flex items-center px-8 py-4 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Login with Facebook
                            </a>
                        </div>

                        <p class="mt-6 text-xs text-center text-gray-500">
                            You must be an admin of the Facebook page you want to connect.
                        </p>
                    @endif
                </div>
            </div>

            @if(!$page || !$page->is_connected)
                <!-- Setup Instructions -->
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Setup Instructions</h3>
                        <div class="space-y-4 text-sm text-gray-600">
                            <div class="flex">
                                <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-semibold text-xs mr-3">1</span>
                                <div>
                                    <p class="font-medium text-gray-900">Create a Facebook App</p>
                                    <p class="mt-1">Visit <a href="https://developers.facebook.com" target="_blank" class="text-indigo-600 hover:text-indigo-800">Facebook Developers</a> and create a new app with Messenger integration.</p>
                                </div>
                            </div>
                            <div class="flex">
                                <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-semibold text-xs mr-3">2</span>
                                <div>
                                    <p class="font-medium text-gray-900">Configure App Settings</p>
                                    <p class="mt-1">Add your App ID and App Secret to the <code class="bg-gray-100 px-1 rounded">.env</code> file in your project.</p>
                                </div>
                            </div>
                            <div class="flex">
                                <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-semibold text-xs mr-3">3</span>
                                <div>
                                    <p class="font-medium text-gray-900">Setup Webhook</p>
                                    <p class="mt-1">Configure webhook URL: <code class="bg-gray-100 px-1 rounded">{{ url('/webhook') }}</code></p>
                                </div>
                            </div>
                            <div class="flex">
                                <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-semibold text-xs mr-3">4</span>
                                <div>
                                    <p class="font-medium text-gray-900">Connect Your Page</p>
                                    <p class="mt-1">Click "Login with Facebook" above to authorize and connect your page.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
