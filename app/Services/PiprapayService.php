<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\PaymentTransaction;

class PiprapayService
{
    protected $apiKey;
    protected $apiUrl;
    protected $callbackUrl;

    public function __construct()
    {
        $this->apiKey = env('PIPRAPAY_API_KEY');
        $this->apiUrl = env('PIPRAPAY_API_URL', 'https://api.piprapay.com');
        $this->callbackUrl = route('payment.callback');
    }

    /**
     * Create a payment link for an order
     */
    public function createPaymentLink(Order $order): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/api/v1/payment/create', [
                'amount' => $order->total_amount,
                'currency' => 'BDT',
                'order_id' => $order->id,
                'customer_name' => $order->customer->name ?? 'Customer',
                'customer_phone' => $order->customer->phone ?? '',
                'customer_email' => $order->customer->email ?? '',
                'callback_url' => $this->callbackUrl,
                'cancel_url' => $this->callbackUrl,
                'description' => 'Order #' . $order->id . ' - ChatCommerce',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Create payment transaction record
                PaymentTransaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => $data['transaction_id'] ?? uniqid('txn_'),
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                    'payment_method' => 'piprapay',
                    'response_data' => json_encode($data),
                ]);

                return [
                    'success' => true,
                    'payment_url' => $data['payment_url'] ?? null,
                    'transaction_id' => $data['transaction_id'] ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Failed to create payment link',
            ];
        } catch (\Exception $e) {
            \Log::error('Piprapay payment creation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Payment gateway error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment callback from Piprapay
     */
    public function verifyPayment(array $callbackData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/api/v1/payment/verify', [
                'transaction_id' => $callbackData['transaction_id'] ?? null,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'status' => $data['status'] ?? 'pending',
                    'transaction_id' => $data['transaction_id'] ?? null,
                    'amount' => $data['amount'] ?? 0,
                ];
            }

            return [
                'success' => false,
                'error' => 'Payment verification failed',
            ];
        } catch (\Exception $e) {
            \Log::error('Piprapay payment verification failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Verification error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handle payment webhook callback
     */
    public function handleWebhook(array $webhookData): bool
    {
        try {
            $transactionId = $webhookData['transaction_id'] ?? null;
            $status = $webhookData['status'] ?? 'pending';

            if (!$transactionId) {
                return false;
            }

            // Find payment transaction
            $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();
            
            if (!$transaction) {
                \Log::warning('Payment transaction not found: ' . $transactionId);
                return false;
            }

            // Update transaction status
            $transaction->update([
                'status' => $status,
                'response_data' => json_encode($webhookData),
            ]);

            // Update order status if payment completed
            if ($status === 'completed' || $status === 'success') {
                $transaction->order->update([
                    'status' => 'confirmed',
                ]);

                // Send confirmation to customer via Messenger
                $this->sendPaymentConfirmation($transaction->order);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Piprapay webhook handling failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment confirmation message
     */
    protected function sendPaymentConfirmation(Order $order)
    {
        try {
            $page = $order->conversation->page;
            $messengerService = new MessengerService();
            
            $message = "✅ Payment received for Order #{$order->id}!\n\n" .
                      "Amount: ৳" . number_format($order->total_amount, 2) . "\n" .
                      "Your order has been confirmed and will be processed soon.\n\n" .
                      "Thank you for your purchase!";

            $messengerService->sendMessage(
                $page->access_token,
                $order->conversation->psid,
                $message
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send payment confirmation: ' . $e->getMessage());
        }
    }
}
