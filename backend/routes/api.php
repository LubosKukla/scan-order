<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Models\Customer;
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



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);


    // Authenticated routes can be added here...
});



Route::middleware(['auth:sanctum', 'customer'])
    ->group(function () {
        Route::get('/customers/{customer}', [CustomerController::class, 'show']);
    });

Route::middleware(['auth:sanctum', 'customer'])
    ->prefix('customers/{customer}')
    ->group(function () {
        // Customer-specific routes

    });

Route::middleware(['auth:sanctum', 'restaurant', 'paid'])
    ->prefix('restaurants/{restaurant}')
    ->group(function () {
        // ďalšie reštauračné endpointy…
    });
