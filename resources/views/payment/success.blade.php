<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
            <p class="text-gray-600 mb-6">
                Thank you for your payment. Your order #{{ $order->id }} has been confirmed.
            </p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Order ID:</span>
                    <span class="font-semibold text-gray-900">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Amount Paid:</span>
                    <span class="font-semibold text-green-600">৳{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <p class="text-sm text-gray-500 mb-6">
                We've sent a confirmation message to your Facebook Messenger. You will receive updates about your order through Messenger.
            </p>

            <div class="text-sm text-gray-600">
                <p>You can close this window now.</p>
                <p class="mt-2">Return to <a href="https://www.facebook.com/messages" class="text-indigo-600 hover:text-indigo-800 font-medium">Facebook Messenger</a></p>
            </div>
        </div>
    </div>
</body>
</html>
