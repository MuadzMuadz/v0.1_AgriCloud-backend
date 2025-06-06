<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Resources\FieldResource;
use App\Models\FarmerWarehouse;
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
    public function store(Request $request)
    {
        $user = auth()->user();

        // Step 1: Check role
        if (!$user || $user->role !== 'farmer') {
            return response()->json(['message' => 'Unauthorized. Only farmers can add fields.'], 403);
        }

        // Step 2: Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'required|numeric|min:0.1',
            'warehouse_id' => 'required|integer|exists:farmer_warehouses,id',
        ]);

        // Step 3: Check ownership of warehouse
        $warehouse = FarmerWarehouse::where('id', $validated['warehouse_id'])
                    ->where('user_id', $user->id)
                    ->first();

        if (!$warehouse) {
            return response()->json(['message' => 'Invalid warehouse or not owned by user.'], 403);
        }

        // Step 4: Create field
        $field = $warehouse->fields()->create([
            'name' => $validated['name'],
            'area' => $validated['area'],
            // bisa tambah kolom lain juga
        ]);

        return response()->json([
            'message' => 'Field created successfully.',
            'data' => $field
        ]);
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
