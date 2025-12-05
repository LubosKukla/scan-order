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

    public function setOpenHours(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'open_hours' => ['required', 'array'],
            'open_hours.*.day_of_week' => ['required', 'integer', 'between:1,7'],
            'open_hours.*.open_time' => ['required', 'date_format:H:i'],
            'open_hours.*.close_time' => ['required', 'date_format:H:i'],
            'open_hours.*.is_closed' => ['nullable', 'boolean'],
        ]);

        foreach ($validatedData['open_hours'] as $hour) {
            $restaurant->openHours()->updateOrCreate(
                ['day_of_week' => $hour['day_of_week']],
                [
                    'open_time' => $hour['open_time'] ?? null,
                    'close_time' => $hour['close_time'] ?? null,
                    'is_closed' => $hour['is_closed']
                        ?? (empty($hour['open_time']) && empty($hour['close_time'])),
                ]
            );
        }

        return response()->json(['message' => 'Open hours updated successfully']);
    }

    public function updateOpenHours(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'open_hours' => ['required', 'array', 'size:7'],
            'open_hours.*.day_of_week' => ['required', 'integer', 'between:1,7'],
            'open_hours.*.open_time' => ['nullable', 'date_format:H:i'],
            'open_hours.*.close_time' => ['nullable', 'date_format:H:i'],
            'open_hours.*.is_closed' => ['nullable', 'boolean'],
        ]);

        $normalizedHours = array_map(
            fn($hour) => [
                'day_of_week' => $hour['day_of_week'],
                'open_time' => $hour['open_time'] ?? null,
                'close_time' => $hour['close_time'] ?? null,
                'is_closed' => $hour['is_closed']
                    ?? (empty($hour['open_time']) && empty($hour['close_time'])),
            ],
            $validatedData['open_hours']
        );

        $restaurant->openHours()->delete();
        $restaurant->openHours()->createMany($normalizedHours);

        return response()->json(['message' => 'Open hours replaced successfully']);
    }

    public function deleteOpenHours(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $restaurant->openHours()->delete();

        return response()->json(['message' => 'All open hours deleted successfully']);
    }
}
