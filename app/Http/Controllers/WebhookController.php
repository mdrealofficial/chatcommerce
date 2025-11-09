<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Customer;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MessengerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $messengerService;

    public function __construct(MessengerService $messengerService)
    {
        $this->messengerService = $messengerService;
    }

    /**
     * Verify webhook
     */
    public function verify(Request $request)
    {
        $verifyToken = config('services.facebook.webhook_verify_token');
        
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('Webhook verified successfully');
            return response($challenge, 200);
        }

        Log::warning('Webhook verification failed');
        return response('Verification failed', 403);
    }

    /**
     * Handle incoming webhook events
     */
    public function handle(Request $request)
    {
        $data = $request->all();
        
        Log::info('Webhook received', ['data' => $data]);

        if (isset($data['object']) && $data['object'] === 'page') {
            foreach ($data['entry'] as $entry) {
                if (isset($entry['messaging'])) {
                    foreach ($entry['messaging'] as $event) {
                        $this->processEvent($event);
                    }
                }
            }
        }

        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Process individual messaging event
     */
    protected function processEvent($event)
    {
        $senderId = $event['sender']['id'] ?? null;
        $recipientId = $event['recipient']['id'] ?? null;

        if (!$senderId || !$recipientId) {
            return;
        }

        // Find the page
        $page = Page::where('page_id', $recipientId)->first();
        
        if (!$page) {
            Log::warning('Page not found for webhook event', ['page_id' => $recipientId]);
            return;
        }

        // Get or create customer
        $customer = $this->getOrCreateCustomer($senderId, $page);

        // Get or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'user_id' => $page->user_id,
                'customer_id' => $customer->id,
                'page_id' => $page->id,
            ],
            [
                'last_message_at' => now(),
                'is_read' => false,
            ]
        );

        // Handle different event types
        if (isset($event['message'])) {
            $this->handleMessage($event['message'], $conversation, $page);
        } elseif (isset($event['postback'])) {
            $this->handlePostback($event['postback'], $conversation, $page, $customer);
        }
    }

    /**
     * Handle incoming message
     */
    protected function handleMessage($message, $conversation, $page)
    {
        $messageText = $message['text'] ?? null;
        $messageId = $message['mid'] ?? null;

        if (!$messageText) {
            return;
        }

        // Store message
        Message::create([
            'conversation_id' => $conversation->id,
            'message_id' => $messageId,
            'sender_type' => 'customer',
            'message' => $messageText,
            'is_read' => false,
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'is_read' => false,
        ]);

        // Check if waiting for address
        $pendingOrder = Order::where('conversation_id', $conversation->id)
            ->where('status', 'pending')
            ->whereNull('delivery_address')
            ->latest()
            ->first();

        if ($pendingOrder) {
            // Customer provided address
            $pendingOrder->update([
                'delivery_address' => $messageText,
                'customer_phone' => $conversation->customer->phone,
            ]);

            // Send payment link
            $this->sendPaymentLink($pendingOrder, $page);
        }
    }

    /**
     * Handle postback (button click)
     */
    protected function handlePostback($postback, $conversation, $page, $customer)
    {
        $payload = json_decode($postback['payload'], true);
        $action = $payload['action'] ?? null;

        if ($action === 'confirm_order') {
            $this->handleConfirmOrder($payload, $conversation, $page, $customer);
        } elseif ($action === 'cancel_order') {
            $this->handleCancelOrder($page, $customer);
        }
    }

    /**
     * Handle order confirmation
     */
    protected function handleConfirmOrder($payload, $conversation, $page, $customer)
    {
        $productId = $payload['product_id'] ?? null;
        $price = $payload['price'] ?? null;

        if (!$productId || !$price) {
            return;
        }

        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        // Create order
        $order = Order::create([
            'user_id' => $page->user_id,
            'customer_id' => $customer->id,
            'conversation_id' => $conversation->id,
            'subtotal' => $price,
            'total' => $price,
            'status' => 'pending',
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $price,
            'quantity' => 1,
            'total' => $price,
        ]);

        // Store bot message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'bot',
            'message' => 'Please provide your delivery address.',
        ]);

        // Ask for delivery address
        $this->messengerService->sendMessage(
            $page->page_access_token,
            $customer->psid,
            'Great! Please provide your delivery address so we can process your order.'
        );
    }

    /**
     * Handle order cancellation
     */
    protected function handleCancelOrder($page, $customer)
    {
        // Store bot message
        Message::create([
            'conversation_id' => Conversation::where('customer_id', $customer->id)
                ->where('page_id', $page->id)
                ->first()->id,
            'sender_type' => 'bot',
            'message' => 'Order cancelled. Feel free to browse other products!',
        ]);

        $this->messengerService->sendMessage(
            $page->page_access_token,
            $customer->psid,
            'Order cancelled. Feel free to browse other products!'
        );
    }

    /**
     * Send payment link to customer
     */
    protected function sendPaymentLink($order, $page)
    {
        // Use Piprapay service to create payment link
        $piprapayService = new \App\Services\PiprapayService();
        $result = $piprapayService->createPaymentLink($order);

        if ($result['success'] && isset($result['payment_url'])) {
            $paymentUrl = $result['payment_url'];
        } else {
            // Fallback to callback URL if Piprapay fails
            $paymentUrl = route('payment.callback', ['order' => $order->id]);
            \Log::warning('Piprapay payment link creation failed: ' . ($result['error'] ?? 'Unknown error'));
        }

        // Send payment button to customer
        $buttons = [
            [
                'type' => 'web_url',
                'url' => $paymentUrl,
                'title' => 'Pay ৳' . number_format($order->total_amount, 2),
            ],
        ];

        $this->messengerService->sendButtonMessage(
            $page->access_token,
            $order->conversation->psid,
            "Your order has been confirmed!\n\nTotal Amount: ৳" . number_format($order->total_amount, 2) . "\n\nClick the button below to complete payment.",
            $buttons
        );

        // Store bot message
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_type' => 'bot',
            'message' => 'Payment link sent. Total: ৳' . number_format($order->total_amount, 2),
        ]);
    }

    /**
     * Get or create customer from Facebook user
     */
    protected function getOrCreateCustomer($senderId, $page)
    {
        $customer = Customer::where('psid', $senderId)
            ->where('user_id', $page->user_id)
            ->first();

        if (!$customer) {
            // Fetch user profile from Facebook
            $profile = $this->messengerService->getUserProfile($page->page_access_token, $senderId);

            $customer = Customer::create([
                'user_id' => $page->user_id,
                'psid' => $senderId,
                'name' => ($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''),
                'profile_pic' => $profile['profile_pic'] ?? null,
            ]);
        }

        return $customer;
    }
}
