<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Http\Resources\CycleResource;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all cycles with their related crop templates and stages
        $cycles = Cycle::with(['cropTemplate', 'cycleStage'])->get();
        return CycleResource::collection($cycles);
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
            'field_id' => 'required|exists:fields,id',
            'crop_template_id' => 'required|exists:crop_templates,id',
            'started_at' => 'nullable|date',
            'status' => 'required|in:pending,active,completed',
        ]);

        // Create a new cycle
        $cycle = Cycle::create($validated);

        return new CycleResource($cycle);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch a single cycle with its related crop template and stages
        $cycle = Cycle::with(['cropTemplate', 'cycleStage'])->findOrFail($id);
        return new CycleResource($cycle);
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
            'field_id' => 'required|exists:fields,id',
            'crop_template_id' => 'required|exists:crop_templates,id',
            'started_at' => 'nullable|date',
            'status' => 'required|in:pending,active,completed',
        ]);

        // Find and update the cycle
        $cycle = Cycle::findOrFail($id);
        $cycle->update($validated);

        return new CycleResource($cycle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the cycle
        $cycle = Cycle::findOrFail($id);
        $cycle->delete();

        return response()->json(['message' => 'Cycle deleted successfully.'], 200);
    }
}
