<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RestaurantBilling;
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
    ->group(function () {

        //kosik pre prihlaseneho uzivatela
        Route::get('/baskets', [BasketController::class, 'getBaskets']);
        Route::post('/restaurants/{restaurant}/basket/items', [BasketController::class, 'addItemToBasket']);
        Route::put('/baskets/{basket}/items/{item}', [BasketController::class, 'updateItemInBasket']);
        Route::delete('/baskets/{basket}/items/{item}', [BasketController::class, 'removeItemFromBasket']);
    });





Route::middleware(['auth:sanctum', 'restaurant', 'paid'])
    ->group(function () {
        Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);
    });

Route::middleware(['auth:sanctum', 'restaurant', 'paid'])
    ->prefix('restaurants/{restaurant}')
    ->group(function () {
        //otvaracie hodiny
        Route::get('/openhours', [RestaurantController::class, 'getOpenHours']);
        Route::post('/openhours', [RestaurantController::class, 'setOpenHours']);
        Route::put('/openhours', [RestaurantController::class, 'updateOpenHours']);
        Route::delete('/openhours', [RestaurantController::class, 'deleteOpenHours']);

        Route::get('/billing', [RestaurantBilling::class, 'getBillingInfo']);
        Route::post('/billing', [RestaurantBilling::class, 'addBillingInfo']);
    });
