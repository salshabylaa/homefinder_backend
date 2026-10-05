<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApplicationController;

Route::post('/register/homeadvisor', [AuthController::class, 'registerHomeadvisor']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/auth/google/redirect', [\App\Http\Controllers\Api\GoogleLoginController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [\App\Http\Controllers\Api\GoogleLoginController::class, 'handleGoogleCallback']);
Route::post('/applications/check-email', [ApplicationController::class, 'checkEmail']); // Public check email
Route::post('/applications', [ApplicationController::class, 'store']); // Public submit
Route::get('/listings', [\App\Http\Controllers\Api\ListingController::class, 'publicIndex']); // Public listings

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::put('/user/password', [AuthController::class, 'changePassword']);
    
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    });

    // Admin & Dashboard Routes
    Route::get('/admin/stats', [\App\Http\Controllers\Api\DashboardController::class, 'index']);
    
    Route::get('/admin/listings', [\App\Http\Controllers\Api\ListingController::class, 'index']);
    Route::post('/admin/listings', [\App\Http\Controllers\Api\ListingController::class, 'store']);
    Route::get('/admin/listings/{id}', [\App\Http\Controllers\Api\ListingController::class, 'show']);
    Route::post('/admin/listings/{id}', [\App\Http\Controllers\Api\ListingController::class, 'update']);
    Route::put('/admin/listings/{id}/status', [\App\Http\Controllers\Api\ListingController::class, 'updateStatus']);
    Route::delete('/admin/listings/{id}', [\App\Http\Controllers\Api\ListingController::class, 'destroy']);
    
    Route::get('/admin/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
    Route::post('/admin/users', [\App\Http\Controllers\Api\UserController::class, 'store']);
    Route::put('/admin/users/{id}', [\App\Http\Controllers\Api\UserController::class, 'update']);
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\Api\UserController::class, 'destroy']);
    
    Route::get('/admin/applications', [ApplicationController::class, 'index']);
    Route::get('/admin/applications/{id}', [ApplicationController::class, 'show']);
    Route::put('/admin/applications/{id}/approve', [ApplicationController::class, 'approve']);
    Route::delete('/admin/applications/{id}', [ApplicationController::class, 'destroy']);
});

Route::get('/create-admin-force', function () {
    $user = \App\Models\User::updateOrCreate(
        ['email' => 'admin@homefinder.id'],
        [
            'name' => 'Super Admin',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]
    );
    return response()->json(['message' => 'Admin created!', 'user' => $user]);
});

