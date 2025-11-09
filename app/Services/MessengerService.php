<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessengerService
{
    protected $graphApiVersion;
    protected $baseUrl;

    public function __construct()
    {
        $this->graphApiVersion = config('services.facebook.graph_version', 'v21.0');
        $this->baseUrl = "https://graph.facebook.com/{$this->graphApiVersion}";
    }

    /**
     * Send a text message to a recipient
     */
    public function sendMessage($pageAccessToken, $recipientId, $message)
    {
        $url = "{$this->baseUrl}/me/messages";

        $data = [
            'recipient' => ['id' => $recipientId],
            'message' => ['text' => $message],
        ];

        return $this->sendRequest($url, $pageAccessToken, $data);
    }

    /**
     * Send a product card with image and buttons
     */
    public function sendProductCard($pageAccessToken, $recipientId, $product)
    {
        $url = "{$this->baseUrl}/me/messages";

        $price = $product->special_price ?? $product->price;
        $imageUrl = $product->image ? asset('storage/' . $product->image) : null;

        $elements = [
            [
                'title' => $product->name,
                'subtitle' => ($product->description ?? '') . "\n\nPrice: ৳" . number_format($price, 2),
                'image_url' => $imageUrl,
                'buttons' => [
                    [
                        'type' => 'postback',
                        'title' => '✅ Confirm Order',
                        'payload' => json_encode([
                            'action' => 'confirm_order',
                            'product_id' => $product->id,
                            'price' => $price,
                        ]),
                    ],
                    [
                        'type' => 'postback',
                        'title' => '❌ Cancel',
                        'payload' => json_encode([
                            'action' => 'cancel_order',
                            'product_id' => $product->id,
                        ]),
                    ],
                ],
            ],
        ];

        $data = [
            'recipient' => ['id' => $recipientId],
            'message' => [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'generic',
                        'elements' => $elements,
                    ],
                ],
            ],
        ];

        return $this->sendRequest($url, $pageAccessToken, $data);
    }

    /**
     * Send quick reply buttons
     */
    public function sendQuickReply($pageAccessToken, $recipientId, $message, $quickReplies)
    {
        $url = "{$this->baseUrl}/me/messages";

        $data = [
            'recipient' => ['id' => $recipientId],
            'message' => [
                'text' => $message,
                'quick_replies' => $quickReplies,
            ],
        ];

        return $this->sendRequest($url, $pageAccessToken, $data);
    }

    /**
     * Send a button message
     */
    public function sendButtonMessage($pageAccessToken, $recipientId, $text, $buttons)
    {
        $url = "{$this->baseUrl}/me/messages";

        $data = [
            'recipient' => ['id' => $recipientId],
            'message' => [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'button',
                        'text' => $text,
                        'buttons' => $buttons,
                    ],
                ],
            ],
        ];

        return $this->sendRequest($url, $pageAccessToken, $data);
    }

    /**
     * Get user profile information
     */
    public function getUserProfile($pageAccessToken, $userId)
    {
        $url = "{$this->baseUrl}/{$userId}";

        try {
            $response = Http::get($url, [
                'fields' => 'first_name,last_name,profile_pic',
                'access_token' => $pageAccessToken,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Error getting user profile: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Subscribe app to page webhooks
     */
    public function subscribeToPage($pageAccessToken, $pageId)
    {
        $url = "{$this->baseUrl}/{$pageId}/subscribed_apps";

        try {
            $response = Http::post($url, [
                'access_token' => $pageAccessToken,
                'subscribed_fields' => 'messages,messaging_postbacks',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error subscribing to page: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send HTTP request to Facebook API
     */
    protected function sendRequest($url, $accessToken, $data)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, array_merge($data, [
                'access_token' => $accessToken,
            ]));

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Messenger API Error: ' . $response->body());

            return [
                'success' => false,
                'error' => $response->json()['error']['message'] ?? 'Unknown error',
            ];
        } catch (\Exception $e) {
            Log::error('Messenger API Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
