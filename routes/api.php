<?php

use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/**
 * Public Routes
 * These routes are open to all users.
 */
/**
 * Google Auth
 * To authenticate users using an OAuth provider, you will need two routes: one for redirecting the user
 * to the OAuth provider, and another for receiving the callback from the provider after authentication
 */
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);

/**
 * Protected Routes
 * These routes require user authentication.
 */
Route::middleware(['auth:api'])->group(function () {

    /**
     * Admin Routes - Protected by Roles
     * These routes require specific roles to access.
     */
    Route::middleware('auth:api')->group(function () {
    //You can check roles too if you want. Just replace the previous lune by this one:
    // Route::middleware('role:admin|seller')->group(function () {

        /**
         * User Routes
         */
        Route::get('/users', [UserController::class, 'index'])->middleware('permission:view users');
        Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:view users');
        Route::post('/users', [UserController::class, 'store'])->middleware('permission:create users');
        Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:edit users');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:delete users');

    });

});
