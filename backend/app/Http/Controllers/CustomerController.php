<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        $user = Auth::user();
        if ($user->id !== $customer->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $customer->loadMissing([
            'address',
            'baskets',
            'reviews',
            'user' => fn($query) => $query->select('id', 'email', 'phone'),
        ]);
        return response()->json([
            'customer' => array_merge(
                $customer->toArray(),
                [
                    'email' => optional($customer->user)->email,
                    'phone' => optional($customer->user)->phone,
                ],
            ),
        ]);
    }
}
