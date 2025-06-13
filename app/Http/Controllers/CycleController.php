<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Http\Resources\CycleResource;
use App\Models\CropTemplate;
use Illuminate\Http\Request;
use App\Models\GrowStages;
use App\Models\CycleStages;
use App\Models\User;
use Carbon\Carbon;

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
        $user = auth()->guard()->user();
        if ($user->role !== 'farmer') {
            return response()->json(['message' => 'Only farmers can create a cycle.'], 403);
        }
        // Validate the request
        $validated = $request->validate([
            'field_id' => 'required|integer ',
            'crop_template_id' => 'required|integer',
            'start_date' => 'required|date',
            'status' => 'nullable|in:pending, started, active,completed',
        ]);

        
        // Create Cycle
        $cycle = Cycle::create([
            'field_id' => $validated['field_id'],
            'crop_template_id' => $validated['crop_template_id'],
            'start_date' => $validated['start_date'],
            'status' => $validated['status'] ?? 'pending',
        ]);
        // Ambil grow stages dari template
        $growStages = GrowStages::where('crop_template_id', $validated['crop_template_id'])->orderBy('id')->get();

        // Convert to CycleStage
        foreach ($growStages as $stage) {
            CycleStages::create([
                'cycle_id' => $cycle->id,
                'stage_name' => $stage->stage_name,
                'expected_action' => $stage->expected_action,
                'description' => $stage->description,
                'day_offset' => $stage->day_offset,
                'started_at' => Carbon::parse($validated['start_date'])->addDays($stage->day_offset),
            ]);
        }

        return response()->json([
            'message' => 'Cycle created and stages generated.',
            'data' => new CycleResource($cycle->load(['cycleStage', 'field', 'CropTemplate']))
        ], 201);
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
