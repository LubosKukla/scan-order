<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function show(Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $restaurant->loadMissing([
            'user',
            'address',
            'typeRestaurant',
            'kitchens',
            'restaurantBilling.plan',
            'reviews',
        ]);

        return response()->json([
            'restaurant' => $restaurant,
        ]);
    }
}
