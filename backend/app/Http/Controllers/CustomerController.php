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
            'user',
            'address',
            'baskets',
            'reviews',
        ]);
        return response()->json([
            'customer' => $customer,
        ]);
    }
}
