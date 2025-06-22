<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCycleRequest;
use App\Http\Requests\UpdateCycleRequest;
use App\Http\Resources\CycleResource;
use App\Http\Resources\listCycleResource;
use App\Services\{StageImport};

use App\Models\Cycle;
use App\Models\GrowStages;
use App\Models\CycleStages;
use Carbon\Carbon;

class CycleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all cycles with their related crop templates and stages
        $cycles = Cycle::all();
        foreach ($cycles as $cycle) {
            $cycle->refreshStatusIfNeeded();
            $this->updateProgress($cycle);
        }
        return listCycleResource::collection($cycles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function cycleWithStage()
    {
        // Fetch all cycles with their related crop templates and stages
        $cycles = Cycle::with(['cropTemplate', 'cycleStage'])->get();
        return CycleResource::collection($cycles);
    }

    public function activeByField($id)
    {
        // Fetch all cycles with their related crop templates and stages
        // Example: filter by 'status' field being 'active'
        $cycles = Cycle::with(['cropTemplate', 'cycleStage'])->where('status', 'active')->where('field_id', $id)->get();
        
        if ($cycles->isEmpty()) {
            return response()->json(['message' => 'No active cycles found for this field'], 404);
        }
        return CycleResource::collection($cycles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCycleRequest $request)
    {
        $user = auth()->guard()->user();
        
        // Validate the request
        $validated = $request->validated();

        
        // Create Cycle
        $cycle = Cycle::create($validated);

        $this->generateStagesFromTemplate($cycle, $validated['start_date']);

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
        $this->updateProgress($cycle);
        return new CycleResource($cycle);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCycleRequest $request, string $id)
    {
        // Validate the request
        $validated = $request->validated();

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

    private function generateStagesFromTemplate(Cycle $cycle, string $startDate): void
    {
        $growStages = GrowStages::where('crop_template_id', $cycle->crop_template_id)->orderBy('id')->get();

        foreach ($growStages as $stage) {
            CycleStages::create([
                'cycle_id' => $cycle->id,
                'stage_name' => $stage->stage_name,
                'expected_action' => $stage->expected_action,
                'description' => $stage->description,
                'day_offset' => $stage->day_offset,
                'start_at' => Carbon::parse($startDate)->addDays($stage->day_offset),
            ]);
        }
    }

    private function updateProgress(Cycle $cycle): void
    {
        $stages = $cycle->cycleStage;
        $totalStages = $stages->count();
        $completedStages = $stages->filter(function ($stage) {
            return Carbon::now()->gte($stage->start_at);
        })->count();

        if ($totalStages > 0) {
            $progress = ($completedStages / $totalStages) * 100;
            $cycle->progress = round($progress, 2);
            $cycle->save();
        }
    }
}
