<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RestaurantController;
use App\Models\Customer;
use App\Models\Restaurant;
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


Route::group([], function () {
    Route::get('/types/restaurant', [RestaurantController::class, 'getTypes']);
    Route::get('/types/kitchen', [RestaurantController::class, 'getKitchens']);
});

Route::middleware(['auth:sanctum', 'customer'])
    ->group(function () {
        Route::get('/customers/{customer}', [CustomerController::class, 'show']);
    });

Route::middleware(['auth:sanctum', 'customer'])
    ->prefix('customers/{customer}')
    ->group(function () {});

Route::middleware(['auth:sanctum', 'restaurant', 'paid'])
    ->group(function () {
        Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);
    });

Route::middleware(['auth:sanctum', 'restaurant', 'paid'])
    ->prefix('restaurants/{restaurant}')
    ->group(function () {
        Route::get('/openhours', [RestaurantController::class, 'getOpenHours']);


        Route::get('/billing', [RestaurantController::class, 'getBillingInfo']);
    });
