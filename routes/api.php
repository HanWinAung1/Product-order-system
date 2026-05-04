<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

// Public login route
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/products', [OrderController::class, 'index']); // Get product list
  Route::post('/orders', [OrderController::class, 'store']);  // Place order
  Route::post('/logout', [AuthController::class, 'logout']);
});