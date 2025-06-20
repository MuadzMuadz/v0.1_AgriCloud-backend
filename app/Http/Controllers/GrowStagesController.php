<?php

namespace App\Http\Controllers;

use App\Models\GrowStages;
use App\Http\Resources\GrowStageResource;
use App\Http\Requests\StoreGrowStagesRequest;
use App\Http\Requests\UpdateGrowStagesRequest;

class GrowStagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all grow stages with their related crop templates
        $growStages = GrowStages::with('cropTemplate')->get();
        return GrowStageResource::collection($growStages);
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
    public function store(StoreGrowStagesRequest $request)
    {
        // Validate the request
        $validated = $request->validated();

        // Create a new grow stage
        $growStage = GrowStages::create($validated);

        return new GrowStageResource($growStage);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch a single grow stage with its related crop template
        $growStage = GrowStages::with('cropTemplate')->findOrFail($id);
        return new GrowStageResource($growStage);
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
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(UpdateGrowStagesRequest $request, string $id)
    {
        // Validate the request
        $validated = $request->validated();

        // Find and update the grow stage
        $growStage = GrowStages::findOrFail($id);
        $growStage->update($validated);

        return new GrowStageResource($growStage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the grow stage
        $growStage = GrowStages::findOrFail($id);
        $growStage->delete();

        return response()->json(['message' => 'Grow stage deleted successfully.'], 200);
    }
}
