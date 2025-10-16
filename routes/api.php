<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;

// // public routes
// Route::post('/admin/login', [AdminAuthController::class, 'login']);

// // protected routes (requires bearer token via auth:admin)
// Route::middleware('auth:admin')->group(function () {
//     Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
//     Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard']);
// });
