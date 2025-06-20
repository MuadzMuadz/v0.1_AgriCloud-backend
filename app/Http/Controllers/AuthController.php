<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdate;
use Illuminate\Support\Str;

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
            'message' => 'Login successful',
            'data' =>[
                'user' => new userResource($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
                ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        $user = auth()->guard()->user();

        // Hapus token aktif aja
        $user->currentAccessToken()->delete();

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

    /**
     * Update the specified resource in storage.
     */    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function updateProfile(UserUpdate $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        // Debugging: cek data profile_photo yang diupload
        Log::debug('Profile photo:', [
            'hasFile' => $request->hasFile('profile_photo'),
            'file' => $request->file('profile_photo')
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        // Jika ada file profile_photo, proses upload
        $profile_photo = null;
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
            @unlink(public_path($user->profile_photo));
            }

            $profilePhoto = $request->file('profile_photo');
            $filename = Str::slug($user->name) . '.' . $profilePhoto->extension();
            $path = $profilePhoto->storeAs("users/{$user->id}/profile", $filename, 'public');
            $profile_photo = "storage/{$path}";
            $validated['profile_photo'] = $profile_photo;
        }
        $user->update($validated);

        return new UserResource($user);
    }
      
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 400);
        }

        $user->update([
            'password' => bcrypt($data['new_password']),
        ]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $data['email'])->first();

        $user->update([
            'password' => bcrypt($data['new_password']),
        ]);

        return response()->json([
            'message' => 'Password reset successfully. You can now log in with your new password.',
        ]);
    }
        
}
