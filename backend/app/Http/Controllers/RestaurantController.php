<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Type_kitchen;
use App\Models\Type_restaurant;
use Illuminate\Http\Request;
use Nette\Utils\Type;

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

    public function getTypes()
    {
        $types = Type_restaurant::all();
        return response()->json(['types' => $types]);
    }

    public function getKitchens()
    {
        $kitchens = Type_kitchen::all();
        return response()->json(['kitchens' => $kitchens]);
    }

    public function getOpenHours(Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $openHours = $restaurant->openHours;

        return response()->json(['open_hours' => $openHours]);
    }
}
