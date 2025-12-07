<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;

class Paid
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user || ! $user->isRestaurant()) {
            abort(403, 'Only restaurant accounts can access this resource.');
        }

        // Získať ID reštaurácie z route parametra alebo z request parametra
        $restaurantId = null;
        $routeRestaurant = $request->route('restaurant');
        if ($routeRestaurant instanceof Restaurant) {
            $restaurantId = $routeRestaurant->id;
        } else {
            $restaurantId = $request->route('restaurant_id') ?? $request->input('restaurant_id');
        }

        $query = $user->restaurants()->with('restaurantBilling');
        if ($restaurantId) {
            $query->whereKey($restaurantId);
        }
        $restaurant = $query->first();

        if (! $restaurant) {
            return response()->json(['message' => 'Restaurant not found for this user.'], 403);
        }
        //mozno vymazat
        // Povoliť prístup k otváracím hodinám aj bez aktívneho billing záznamu
        if ($request->is('api/restaurants/*/openhours')) {
            return $next($request);
        }

        if (! $restaurant->restaurantBilling) {
            return response()->json(['message' => 'Billing record missing.'], 402);
        }

        $billing = $restaurant->restaurantBilling;
        $isTrial = $billing->trial_ends_at && now()->lte($billing->trial_ends_at);
        $isPaid  = in_array($billing->subscription_status, ['active', 'paid'], true);

        if (! $isTrial && ! $isPaid) {
            return response()->json([
                'message' => 'Subscription required.',
                'trial_ends_at' => $billing->trial_ends_at,
                'subscription_status' => $billing->subscription_status,
            ], 402);
        }

        return $next($request);
    }
}
