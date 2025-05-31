<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DocumentController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/email/verify', [AuthController::class, 'verifyEmail']);
Route::get('/search', [SearchController::class, 'searchPreview']);

// Protected user routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'update']);
    Route::post('/keywords', [UserController::class, 'storeKeyword']);
    Route::get('/documents/{id}', [SearchController::class, 'viewDocument']);
});

// Admin-only routes
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {
    Route::post('/documents', [DocumentController::class, 'upload']);
    Route::get('/documents', [DocumentController::class, 'index']);
});

use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user(); // ✅ correct
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user(); // ✅ correct
});

