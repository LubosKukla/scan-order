<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantBilling extends Controller
{
    public function getBillingInfo(Restaurant $restaurant)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $billing = $restaurant->restaurantBilling;

        return response()->json([
            'billing' => $billing,
        ]);
    }

    public function addBillingInfo(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'ico' => 'required|string|max:50',
            'dic' => 'required|string|max:50',
            'ic_dph' => 'nullable|string|max:50',
            'iban' => 'required|string|max:50',
            'company_name' => 'required|string|max:255',
        ]);

        $billing = $restaurant->restaurantBilling()->updateOrCreate(
            ['restaurant_id' => $restaurant->id],
            [
                'ico' => $data['ico'],
                'dic' => $data['dic'],
                'ic_dph' => $data['ic_dph'] ?? null,
                'iban' => $data['iban'],
                'company_name' => $data['company_name'],
            ]
        );

        return response()->json([
            'message' => 'Billing information added successfully',
            'billing' => $billing,
        ], 201);
    }
}
