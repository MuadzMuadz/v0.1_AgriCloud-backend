<?php

namespace App\Http\Controllers;

use App\Models\FarmerWarehouse;
use App\Http\Resources\FarmerWarehouseResource;
use App\Http\Requests\StoreFarmerWarehouseRequest;
use App\Http\Requests\UpdateFarmerWarehouseRequest;
use Illuminate\Support\Str;
use Laravel\Sanctum\Guard;
use Illuminate\Support\Facades\Storage;

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
        $user = auth()->guard()->user();
        $validated['user_id'] = $user->id;

        // dd($request->file('photos'));

        $warehouse = FarmerWarehouse::create($validated);

        if ($request->hasFile('photos')) {
            $error = $this->handleWarehouseImages($warehouse, $request->file('photos'));
            if ($error) return $error;
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
        $user = auth()->guard()->user();
        $validated = $request->validated();

        $warehouse = FarmerWarehouse::findOrFail($id);
        $warehouse->update($validated);

        

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

    private function handleWarehouseImages($warehouse, $images)
    {
        $user = auth()->guard()->user();
        $warehouse->load('photos');
        $existingCount = $warehouse->photos->count();

        if ($existingCount + count($images) > 3) {
            return response()->json(['error' => 'Maksimal 3 gambar diperbolehkan.'], 400);
        }

        // Delete existing
        foreach ($warehouse->photos as $image) {
            Storage::delete("public/users/{$user->id}/warehouse/fw_{$warehouse->id}/{$image->filename}");
            $image->delete();
        }

        foreach ($images as $i => $file) {
            $filename = Str::slug($warehouse->name, '_') . "_".($i+1).".".$file->getClientOriginalExtension();
            $path = "public/users/{$user->id}/warehouse/fw_{$warehouse->id}/";
            $file->storeAs($path, $filename);

            $warehouse->photos()->create(['filename' => $filename]);
        }

        return null;
    }

}
