<?php

namespace App\Http\Controllers;

use App\Models\User;
use illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use League\Config\Exception\ValidationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,farmer',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    
}
