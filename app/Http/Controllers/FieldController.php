<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Http\Resources\FieldResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){   
        $fields = Field::all();
        return FieldResource::collection($fields);
    }

    public function myFields(){
        $user = auth()->guard()->user();
        // Fetch fields for the authenticated user
        $fields = Field::where('user_id', $user->id)->get();
        
        if ($fields->isEmpty()) {
            return response()->json(['message' => 'No fields found in yout account'], 404);
        }
        
        return FieldResource::collection($fields);
    }

    public function listByFarmer($farmerId){
        // Fetch fields for a specific farmer
        $fields = Field::where('user_id', $farmerId)->get();
        
        if ($fields->isEmpty()) {
            return response()->json(['message' => 'No fields found for this farmer'], 404);
        }
        
        return FieldResource::collection($fields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreFieldRequest $request){
        $user = auth()->guard()->user();

        // dd([
        //     'validated' => $request->validated(),
        //     'has_file' => $request->hasFile('thumbnail'),
        //     'file' => $request->file('thumbnail'),
        // ]);

        $validated = $request->validated();
        if (Field::where('name', $validated['name'])->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Field with this name already exists.'], 409);
        }

        $field = $user->fields()->create($validated);
        
        if ($request->hasFile('thumbnail')) {
            // Handle the image upload
            $file = $request->file('thumbnail');
            $slugName = Str::slug($field->name);
            $ext = $file->getClientOriginalExtension();
            $fileName = "{$slugName}.{$ext}";
            $path = "users/{$user->id}/lahan/{$slugName}/{$fileName}";

            // Store the file in the public disk
            Storage::disk('public')->put($path, file_get_contents($file));

            // Save the image path to the field
            $field->update(['thumbnail' => $path]);
            
        }

        return new FieldResource($field);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        // Fetch a single field with its related farmer
        $field = Field::with('user')->findOrFail($id);
        return new FieldResource($field);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function myField(string $id){
        $user = auth()->guard()->user();
        // Check if the user is authorized to view this field
        $field = Field::with('user')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return new FieldResource($field);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFieldRequest $request, string $id){
        $user = auth()->guard()->user();
        // dd($request->all()); 

        $validated = $request->validated();

        $field = Field::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $validated = $request->validated();
        if (isset($validated['name']) && Field::where('name', $validated['name'])->where('user_id', $user->id)->where('id', '!=', $id)->exists()) {
            return response()->json(['message' => 'Field with this name already exists.'], 409);
        }
        // Update the field with validated data
        $field->update($validated);

        if ($request->hasFile('thumbnail')) {
            // Handle the image upload
            $file = $request->file('thumbnail');
            $slugName = Str::slug($field->name);
            $ext = $file->getClientOriginalExtension();
            $fileName = "{$slugName}.{$ext}";
            $path = "users/{$user->id}/lahan/{$slugName}/{$fileName}";

            // Store the file in the public disk
            Storage::disk('public')->put($path, file_get_contents($file));

            // Save the image path to the field
            $field->update(['thumbnail' => $path]);
        }

        return new FieldResource($field);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        // Find and delete the field
        $field = Field::findOrFail($id);
        $field->delete();

        return response()->json(['message' => 'Field deleted successfully.'], 200);
    }
}
