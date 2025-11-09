<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MessengerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'conversation.page', 'items.product'])
            ->where('user_id', auth()->id())
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by customer name or order ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('id', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['customer', 'conversation.page', 'items.product', 'paymentTransaction']);

        return view('orders.show', compact('order'));
    }

    /**
     * Update the order status and send notification
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        // Send notification to customer via Messenger
        if ($request->status !== $oldStatus) {
            $this->sendStatusNotification($order, $request->status);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order status updated successfully!');
    }

    /**
     * Handle payment callback from Piprapay
     */
    public function paymentCallback(Request $request)
    {
        $piprapayService = new \App\Services\PiprapayService();
        
        // Handle webhook callback
        if ($request->isMethod('post')) {
            $success = $piprapayService->handleWebhook($request->all());
            
            if ($success) {
                return response()->json(['status' => 'success']);
            }
            
            return response()->json(['status' => 'failed'], 400);
        }

        // Handle redirect callback (user returns from payment page)
        $orderId = $request->get('order');
        $transactionId = $request->get('transaction_id');

        if ($orderId && $transactionId) {
            $order = Order::find($orderId);
            
            if ($order) {
                // Verify payment
                $result = $piprapayService->verifyPayment([
                    'transaction_id' => $transactionId,
                ]);

                if ($result['success'] && $result['status'] === 'completed') {
                    return view('payment.success', ['order' => $order]);
                }
            }
        }

        return view('payment.failed');
    }

    /**
     * Send status update notification to customer
     */
    protected function sendStatusNotification(Order $order, string $status)
    {
        $messages = [
            'confirmed' => '✅ Your order #' . $order->id . ' has been confirmed! We are preparing your items.',
            'processing' => '📦 Your order #' . $order->id . ' is being processed.',
            'shipped' => '🚚 Great news! Your order #' . $order->id . ' has been shipped and is on the way!',
            'delivered' => '🎉 Your order #' . $order->id . ' has been delivered! Thank you for shopping with us!',
            'cancelled' => '❌ Your order #' . $order->id . ' has been cancelled. Contact us if you have questions.',
        ];

        if (isset($messages[$status])) {
            $page = $order->conversation->page;
            $messengerService = new MessengerService();
            
            try {
                $messengerService->sendMessage(
                    $page->access_token,
                    $order->conversation->psid,
                    $messages[$status]
                );
            } catch (\Exception $e) {
                Log::error('Failed to send status notification: ' . $e->getMessage());
            }
        }
    }
}
