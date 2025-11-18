<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\ValidationException;

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
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'string', 'same:password'],
        ]);

        try {
            $user = User::create([
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Chyba pri registrácii používateľa'], 500);
        }

        Customer::create([
            'user_id' => $user->id,
            'name' => $credentials['name'],
            'surname' => $credentials['surname'],
        ]);


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
