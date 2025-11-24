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
            'address',
            'typeRestaurant',
            'kitchens',
            'restaurantBilling.plan',
            'reviews',
            'user' => fn($query) => $query->select('id', 'email', 'phone'),
        ]);

        return response()->json([
            'restaurant' => array_merge(
                $restaurant->toArray(),
                [
                    'email' => optional($restaurant->user)->email,
                    'phone' => optional($restaurant->user)->phone,
                ],
            ),
        ]);
    }
}
