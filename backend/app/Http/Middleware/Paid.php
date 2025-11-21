<?php

namespace App\Http\Middleware;

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

        // Ak máš viac reštaurácií, vyber si správnu podľa parametra,
        // tu beriem prvú.
        $restaurant = $user->restaurants()->with('restaurantBilling')->first();
        if (! $restaurant || ! $restaurant->restaurantBilling) {
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
            ], 402); // Payment Required
        }

        return $next($request);
    }
}
