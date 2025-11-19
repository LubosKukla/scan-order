<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Models\Type_kitchen;
use App\Models\Type_restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\ValidationException;
use Nette\Utils\Type;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request using the web guard so Sanctum can issue cookies.
     */
    public function registerCustomer(Request $request)
    {
        // Registration logic for a customer goes here
        $credentials = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accept_gdpr' => ['required', 'accepted']
        ]);

        try {
            $user = User::create([
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
                'accept_gdpr' => $request->boolean('accept_gdpr'),
            ]);

            Customer::create([
                'user_id' => $user->id,
                'name' => $credentials['name'],
                'surname' => $credentials['surname'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Chyba pri registrácii používateľa'], 500);
        }




        Auth::login($user);
        $request->session()->regenerate();

        return response()->json($user);
    }

    public function registerRestaurant(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_boss' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accept_gdpr' => ['required', 'accepted'],

            'street' => ['required', 'string', 'max:255'],
            'number_of_building' => ['required', 'string', 'max:50'],
            'PSC' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],

            'type_restaurant_id'     => ['required_without:type_restaurant_custom', 'nullable', 'integer', 'exists:type_restaurant,id'],
            'type_restaurant_custom' => ['required_without:type_restaurant_id', 'string', 'min:2', 'max:255'],

            'type_kitchen_ids'       => ['required_without:type_kitchen_custom', 'array'],
            'type_kitchen_ids.*'     => ['integer', 'exists:type_kitchen,id'],
            'type_kitchen_custom'    => ['required_without:type_kitchen_ids', 'string', 'min:2', 'max:255'],

            //este treba doplnit otvaracie hodiny 
            //doplnit description, logo a platbu predvolenu
        ]);

        $user = null;

        DB::transaction(function () use ($request, $data, &$user) {

            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'accept_gdpr' => $request->boolean('accept_gdpr'),
                'phone' => $data['phone'],
            ]);

            $address = Address::create([
                'street' => $data['street'],
                'number_of_building' => $data['number_of_building'],
                'PSC' => $data['PSC'],
                'city' => $data['city'],
            ]);

            $typeRestaurantId = null;

            if (!empty($data['type_restaurant_id'])) {
                // vybral existujúci typ
                $typeRestaurantId = $data['type_restaurant_id'];
            } elseif (!empty($data['type_restaurant_custom'])) {
                // zadal nový typ – uložíme ho do tabuľky Type_restaurant
                $typeRestaurant = Type_restaurant::firstOrCreate([
                    'type' => $data['type_restaurant_custom'],
                ]);
                $typeRestaurantId = $typeRestaurant->id;
            }

            $restaurant = Restaurant::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'name' => $data['name'],
                'name_boss' => $data['name_boss'] ?? null,
                'type_restaurant_id' => $typeRestaurantId,
            ]);

            $kitchenIds = $data['type_kitchen_ids'] ?? [];
            if (!empty($data['type_kitchen_custom'])) {
                $typeKitchen = Type_kitchen::firstOrCreate([
                    'type' => $data['type_kitchen_custom'],
                ]);
                $kitchenIds[] = $typeKitchen->id;
            }

            if (!empty($kitchenIds)) {
                $restaurant->kitchens()->sync($kitchenIds);
            }
        });

        Auth::login($user);
        $request->session()->regenerate();
        return response()->json($user);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Neplatné prihlasovacie údaje'], 401);
        }

        $request->session()->regenerate();

        return response()->json(Auth::user());
    }

    /**
     * Return the currently authenticated user for clients that hold a Sanctum cookie.
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->loadMissing([
                'customer',
                'restaurants',
            ]),
        ]);
    }

    /**
     * Destroy the current Sanctum session and session cookies.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}
