<?php
/**
 * Routes/shop.php
 */

use Illuminate\Support\Facades\Route;
use Plugin\GuestOrderQuery\Controllers\GuestOrderController;

Route::get('/guest_order_query', [GuestOrderController::class, 'index'])->name('guest_order_query');
Route::post('/guest_order_query/search', [GuestOrderController::class, 'search'])->name('guest_order_query.search');