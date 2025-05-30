<?php

namespace App\Http\Controllers;

use App\Models\CropTemplate;
use App\Http\Resources\CropTemplateResource;
use Illuminate\Http\Request;

class CropTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all crop templates with their grow stages
        $cropTemplates = CropTemplate::with('growStage')->get();
        return CropTemplateResource::collection($cropTemplates);
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
            'name' => 'required|string|max:255|unique:crop_templates,name',
            'description' => 'nullable|string',
        ]);

        // Create a new crop template
        $cropTemplate = CropTemplate::create($validated);

        return new CropTemplateResource($cropTemplate);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch a single crop template with its grow stages
        $cropTemplate = CropTemplate::with('growStage')->findOrFail($id);
        return new CropTemplateResource($cropTemplate);
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
            'name' => 'required|string|max:255|unique:crop_templates,name,' . $id,
            'description' => 'nullable|string',
        ]);

        // Find and update the crop template
        $cropTemplate = CropTemplate::findOrFail($id);
        $cropTemplate->update($validated);

        return new CropTemplateResource($cropTemplate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the crop template
        $cropTemplate = CropTemplate::findOrFail($id);
        $cropTemplate->delete();

        return response()->json(['message' => 'Crop template deleted successfully.'], 200);
    }
}
