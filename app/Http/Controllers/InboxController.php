<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\Page;
use App\Services\MessengerService;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    protected $messengerService;

    public function __construct(MessengerService $messengerService)
    {
        $this->messengerService = $messengerService;
    }

    public function index()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->with(['customer', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return view('inbox.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        // Mark as read
        $conversation->update(['is_read' => true]);
        $conversation->messages()->where('sender_type', 'customer')->update(['is_read' => true]);

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
        $products = Product::where('user_id', auth()->id())
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get();

        return view('inbox.show', compact('conversation', 'messages', 'products'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $page = Page::where('user_id', auth()->id())->where('is_connected', true)->first();

        if (!$page) {
            return back()->with('error', 'No Facebook page connected.');
        }

        // Send message via Messenger API
        $result = $this->messengerService->sendMessage(
            $page->page_access_token,
            $conversation->customer->psid,
            $request->message
        );

        if ($result['success']) {
            // Store message in database
            Message::create([
                'conversation_id' => $conversation->id,
                'message_id' => $result['data']['message_id'] ?? null,
                'sender_type' => 'seller',
                'message' => $request->message,
                'is_read' => true,
            ]);

            $conversation->update(['last_message_at' => now()]);

            return back()->with('success', 'Message sent successfully!');
        }

        return back()->with('error', 'Failed to send message. ' . ($result['error'] ?? ''));
    }

    public function sendProduct(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::where('id', $request->product_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $page = Page::where('user_id', auth()->id())->where('is_connected', true)->first();

        if (!$page) {
            return back()->with('error', 'No Facebook page connected.');
        }

        // Send product card via Messenger API
        $result = $this->messengerService->sendProductCard(
            $page->page_access_token,
            $conversation->customer->psid,
            $product
        );

        if ($result['success']) {
            // Store message in database
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'seller',
                'message' => "Sent product: {$product->name} - ৳" . number_format($product->current_price, 2),
                'is_read' => true,
            ]);

            $conversation->update(['last_message_at' => now()]);

            return back()->with('success', 'Product sent successfully!');
        }

        return back()->with('error', 'Failed to send product. ' . ($result['error'] ?? ''));
    }
}
