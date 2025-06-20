<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of all user.
     */
    public function index()
    {
        // Fetch all users
        $users = User::all();

        return UserResource::collection($users);
    }
    /**
     * Display a listing of farmers.
     */
    public function farmerList()
    {
        // Fetch all users
        $users = User::where('role', 'farmer')->get();

        return UserResource::collection($users);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        // Buat user dulu, tanpa profile_photo
        $user = User::create(Arr::except($validated, ['profile_photo']));

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch a single user
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(UserRequest $request, string $id){
        
        $user = User::findOrFail($id);
        $validated = $request->validated();
        dd($request->file('profile_photo'));

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');
            dd($request->file('profile_photo'));
            $filename = Str::uuid() . '.' . $profilePhoto->extension();
            $profilePhoto->move(public_path("users/{$user->id}/profile"), $filename);
            $user->profile_photo = "users/{$user->id}/profile/" . $filename; 
        }

        $user->update($validated);

        return new UserResource($user);
        Log::error('Leave Create Error:'. $e->getMessage());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the user
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.'], 200);
    }
}
