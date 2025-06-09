<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ========================================
// PUBLIC ROUTES (No Authentication Required)
// ========================================

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public access to view categories and cars

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/cars', [CarController::class, 'index']); // Use the public CarController
Route::get('/cars/{id}', [CarController::class, 'show']); // Use the public CarController


Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::post('/cars', [\App\Http\Controllers\Admin\CarController::class, 'store']);
    Route::put('/cars/{id}', [\App\Http\Controllers\Admin\CarController::class, 'update']);
    Route::delete('/cars/{id}', [\App\Http\Controllers\Admin\CarController::class, 'destroy']);
});
// ========================================
// PROTECTED ROUTES (Authentication Required)
// ========================================

Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // User bookings (bonus feature)
    // Route::post('/bookings', [BookingController::class, 'store']);
    // Route::get('/bookings', [BookingController::class, 'index']);
});

// ========================================
// ADMIN ONLY ROUTES
// ========================================

Route::middleware(['auth:sanctum', 'check.role:admin'])->prefix('admin')->group(function () {
    // Category management (using the public CategoryController for simplicity, assuming admin can also use it)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Car management (using the Admin\CarController)
    Route::apiResource('cars', \App\Http\Controllers\Admin\CarController::class);
});

// ========================================
// OTHER ROUTES (Keep existing if needed)
// ========================================

// Users with API Key
Route::middleware(ApiKeyMiddleware::class)->group(function () {
    Route::apiResource('/users', UserController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    // ...
    Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store']);
    Route::get('/bookings', [\App\Http\Controllers\BookingController::class, 'index']);
});
