<?php
/**
 * Routes/admin.php
 */

use Illuminate\Support\Facades\Route;
use Plugin\GuestOrderQuery\Controllers\GuestOrderController;

Route::get('/routes', [GuestOrderController::class, 'getRoutes'])->name('routes');
