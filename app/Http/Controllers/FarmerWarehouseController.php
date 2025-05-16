<?php

namespace App\Http\Controllers;

use App\Models\FarmerWarehouse;
use App\Http\Resources\FarmerWarehouseResource;
use Illuminate\Http\Request;

class FarmerWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all farmer warehouses with their related user and fields
        $farmerWarehouses = FarmerWarehouse::with(['userId', 'field'])->get();
        return FarmerWarehouseResource::collection($farmerWarehouses);
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
        // Validate the request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255|unique:farmer_warehouses,name',
            'location_url' => 'required|string',
        ]);

        // Create a new farmer warehouse
        $farmerWarehouse = FarmerWarehouse::create($validated);

        return new FarmerWarehouseResource($farmerWarehouse);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch a single farmer warehouse with its related user and fields
        $farmerWarehouse = FarmerWarehouse::with(['userId', 'field'])->findOrFail($id);
        return new FarmerWarehouseResource($farmerWarehouse);
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
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255|unique:farmer_warehouses,name,' . $id,
            'location_url' => 'nullable|string',
        ]);

        // Find and update the farmer warehouse
        $farmerWarehouse = FarmerWarehouse::findOrFail($id);
        $farmerWarehouse->update($validated);

        return new FarmerWarehouseResource($farmerWarehouse);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the farmer warehouse
        $farmerWarehouse = FarmerWarehouse::findOrFail($id);
        $farmerWarehouse->delete();

        return response()->json(['message' => 'Farmer warehouse deleted successfully.'], 200);
    }
}
