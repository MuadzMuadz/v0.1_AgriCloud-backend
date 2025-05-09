<?php

namespace App\Http\Controllers;

use App\Models\CycleStages;
use App\Http\Resources\CycleStageResource;
use Illuminate\Http\Request;

class CycleStagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all cycle stages with their related cycles
        $cycleStages = CycleStages::with('cycle')->get();
        return CycleStageResource::collection($cycleStages);
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
            'cycle_id' => 'required|exists:cycles,id',
            'stage_name' => 'required|string|max:255',
            'day_offset' => 'nullable|integer',
            'expected_action' => 'required|string',
            'description' => 'nullable|string',
            'started_at' => 'required|date',
        ]);

        // Create a new cycle stage
        $cycleStage = CycleStages::create($validated);

        return new CycleStageResource($cycleStage);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch a single cycle stage with its related cycle
        $cycleStage = CycleStages::with('cycle')->findOrFail($id);
        return new CycleStageResource($cycleStage);
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
            'cycle_id' => 'required|exists:cycles,id',
            'stage_name' => 'required|string|max:255',
            'day_offset' => 'nullable|integer',
            'expected_action' => 'required|string',
            'description' => 'nullable|string',
            'started_at' => 'required|date',
        ]);

        // Find and update the cycle stage
        $cycleStage = CycleStages::findOrFail($id);
        $cycleStage->update($validated);

        return new CycleStageResource($cycleStage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the cycle stage
        $cycleStage = CycleStages::findOrFail($id);
        $cycleStage->delete();

        return response()->json(['message' => 'Cycle stage deleted successfully.'], 200);
    }
}
