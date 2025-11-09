<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Conversation;
use App\Models\Page;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get statistics
        $totalProducts = Product::where('user_id', $user->id)->count();
        
        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $confirmedOrders = Order::where('user_id', $user->id)->where('status', 'confirmed')->count();
        $deliveredOrders = Order::where('user_id', $user->id)->where('status', 'delivered')->count();
        
        $todaySales = Order::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->sum('total');
        
        // Check if page is connected
        $pageConnected = Page::where('user_id', $user->id)->where('is_connected', true)->exists();
        
        // Get last 5 recent chats
        $recentChats = Conversation::where('user_id', $user->id)
            ->with(['customer', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'confirmedOrders',
            'deliveredOrders',
            'todaySales',
            'pageConnected',
            'recentChats'
        ));
    }
}
