<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Failed</h1>
            <p class="text-gray-600 mb-6">
                Unfortunately, we couldn't process your payment. Please try again or contact us for assistance.
            </p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-700">
                    <strong>What you can do:</strong>
                </p>
                <ul class="text-sm text-gray-600 mt-2 space-y-1 text-left">
                    <li>• Check your payment information and try again</li>
                    <li>• Make sure you have sufficient balance</li>
                    <li>• Contact us through Messenger for help</li>
                </ul>
            </div>

            <div class="space-y-3">
                <button onclick="window.history.back()" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                    Try Again
                </button>
                
                <a href="https://www.facebook.com/messages" class="block w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Contact Us on Messenger
                </a>
            </div>

            <div class="mt-6 text-sm text-gray-600">
                <p>Need help? We're here to assist you!</p>
            </div>
        </div>
    </div>
</body>
</html>
