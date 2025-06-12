<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldRequest;
use App\Models\Field;
use App\Http\Resources\FieldResource;
use App\Models\User;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all fields with their related farmer warehouse
        $fields = Field::with('farmerwarehouse')->get();
        return FieldResource::collection($fields);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFieldRequest $request)
    {
        // Get the authenticated user
        $user = auth()->guard()->user();

        // Validate request using StoreFieldRequest
        $validated = $request->validated();

        // Create the field and associate it with the user
        $field = $user->fields()->create($validated);

        return response()->json([
            'message' => 'Field created successfully.',
            'data' => new FieldResource($field)
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch a single field with its related farmer warehouse
        $field = Field::with('farmerwarehouse')->findOrFail($id);
        return new FieldResource($field);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $validated = $request->validate([
            'farmer_warehouse_id' => 'required|exists:farmer_warehouses,id',
            'name' => 'required|string|max:255|unique:fields,name,' . $id,
            'area' => 'required|numeric|min:0',
        ]);

        // Find and update the field
        $field = Field::findOrFail($id);
        $field->update($validated);

        return new FieldResource($field);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the field
        $field = Field::findOrFail($id);
        $field->delete();

        return response()->json(['message' => 'Field deleted successfully.'], 200);
    }
}
