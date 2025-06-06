<?php

namespace App\Http\Controllers;

use App\Models\FarmerWarehouse;
use App\Http\Resources\FarmerWarehouseResource;
use App\Http\Requests\StoreFarmerWarehouseRequest;
use App\Http\Requests\UpdateFarmerWarehouseRequest;
use Laravel\Sanctum\Guard;

class FarmerWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farmerWarehouses = FarmerWarehouse::with(['user'])->get();
        return FarmerWarehouseResource::collection($farmerWarehouses);
    }

    /**
     * Store a newly created resource in storage.
     * @param  mixed $request
     * @return void
     */
    public function store(StoreFarmerWarehouseRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->guard()->id();

        $warehouse = FarmerWarehouse::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('farmer_warehouses/photos', 'public');
                $warehouse->photos()->create(['path' => $path]);
            }
        }

        return new FarmerWarehouseResource($warehouse);
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
     * Update the specified resource in storage.
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(UpdateFarmerWarehouseRequest $request, string $id)
    {
        $validated = $request->validated();

        $warehouse = FarmerWarehouse::findOrFail($id);
        $warehouse->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('farmer_warehouses/photos', 'public');
                $warehouse->photos()->create(['path' => $path]);
            }
        }

        return new FarmerWarehouseResource($warehouse);
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
