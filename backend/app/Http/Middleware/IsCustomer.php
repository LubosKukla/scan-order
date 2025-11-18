<?php

namespace App\Http\Middleware;

use Closure;

class IsCustomer
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if (! $user || ! $user->isCustomer()) {
            abort(403);
        }
        return $next($request);
    }
}
