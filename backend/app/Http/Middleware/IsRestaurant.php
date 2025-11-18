<?php

namespace App\Http\Middleware;

use Closure;

class IsRestaurant
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if (! $user || ! $user->isRestaurant()) {
            abort(403);
        }
        return $next($request);
    }
}
