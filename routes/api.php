<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\JsonSearchController;

// ------------------------------
// 📢 Public routes
// ------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/email/verify', [AuthController::class, 'verifyEmail']);
Route::get('/search', [SearchController::class, 'searchPreview']);


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Marks user as verified
    return response()->json(['message' => 'Email verified successfully.']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');


// ------------------------------
// 🔒 Protected user routes (auth:sanctum)
// ------------------------------
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'update']);

    // Keywords
    Route::post('/keywords', [UserController::class, 'storeKeyword']);
    Route::get('/keywords', [UserController::class, 'listKeywords']); // optional

    // Documents
    Route::get('/documents/{id}', [SearchController::class, 'viewDocument']);
    Route::get('/documents', [SearchController::class, 'index']);

    // Default user route (optional)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// ------------------------------
// 🛠 Admin-only routes
// ------------------------------
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {
    Route::post('/documents', [DocumentController::class, 'upload']);
    Route::get('/documents', [DocumentController::class, 'index']);
});

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search']);
Route::get('/json-search', [JsonSearchController::class, 'search']);



