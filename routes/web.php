<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Page connection routes
    Route::get('/pages/connect', [PageController::class, 'connect'])->name('pages.connect');
    Route::get('/pages/callback', [PageController::class, 'callback'])->name('pages.callback');
    Route::post('/pages/disconnect', [PageController::class, 'disconnect'])->name('pages.disconnect');
    
    // Product routes
    Route::resource('products', ProductController::class);
    
    // Inbox routes
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{conversation}', [InboxController::class, 'show'])->name('inbox.show');
    Route::post('/inbox/{conversation}/send', [InboxController::class, 'send'])->name('inbox.send');
    Route::post('/inbox/{conversation}/send-product', [InboxController::class, 'sendProduct'])->name('inbox.send-product');
    
    // Order routes
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});

// Webhook routes (no auth middleware)
Route::get('/webhook', [WebhookController::class, 'verify'])->name('webhook.verify');
Route::post('/webhook', [WebhookController::class, 'handle'])->name('webhook.handle');

// Payment callback route
Route::get('/payment/callback', [OrderController::class, 'paymentCallback'])->name('payment.callback');

require __DIR__.'/auth.php';
