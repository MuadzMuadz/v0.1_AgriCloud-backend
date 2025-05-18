<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
            // 'role' => 'required|in:admin,farmer',
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
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $user->tokens()->delete(); // Revoke all previous tokens
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
        $user = auth()->guard()->user();

        // Hapus token aktif aja
        $user->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
 

    /**
     * Display the specified resource.
     */
    public function getUser(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => new UserResource($user),
        ]);
    }

    public function updateUser(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone_number' => 'sometimes|string',
            'password' => 'sometimes|string|min:6',
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => new UserResource($user),
        ]);
    }
        
        
}
