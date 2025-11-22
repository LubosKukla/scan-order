<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function show(Request $request, $customerId)
    {
        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        // Logika na zobrazenie informácií o zákazníkovi
        return response()->json([
            'message' => "Zobrazenie informácií o zákazníkovi s ID: {$customerId}"
        ]);
    }
}
