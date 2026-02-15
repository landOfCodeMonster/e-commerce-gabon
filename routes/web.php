<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Webhooks\PayPalWebhookController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Client routes
Route::middleware(['auth', 'verified'])->prefix('client')->name('client.')->group(function () {
    Route::view('/dashboard', 'client.dashboard')->name('dashboard');

    Route::view('/orders', 'client.orders.index')->name('orders.index');
    Route::view('/orders/create', 'client.orders.create')->name('orders.create');
    Route::get('/orders/{order}', function (Order $order) {
        abort_if($order->user_id !== auth()->id(), 403);
        return view('client.orders.show', compact('order'));
    })->name('orders.show');

    Route::view('/invoices', 'client.invoices.index')->name('invoices.index');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    Route::view('/profile', 'profile')->name('profile');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    Route::view('/orders', 'admin.orders.index')->name('orders.index');
    Route::get('/orders/{order}', function (Order $order) {
        return view('admin.orders.show', compact('order'));
    })->name('orders.show');

    Route::view('/payments', 'admin.payments.index')->name('payments.index');
    Route::view('/users', 'admin.users.index')->name('users.index');
    Route::view('/settings', 'admin.settings')->name('settings');
});

// Redirect default dashboard based on role
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('client.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Payment webhooks (excluded from CSRF in bootstrap/app.php)
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('webhooks.stripe');
Route::post('/webhooks/paypal', [PayPalWebhookController::class, 'handle'])->name('webhooks.paypal');

require __DIR__.'/auth.php';
