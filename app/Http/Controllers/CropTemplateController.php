<?php

namespace App\Http\Controllers;

use App\Models\CropTemplate;
use App\Http\Resources\CropTemplateResource;
use App\Http\Requests\StoreCropTemplateRequest;
use App\Http\Requests\UpdateCropTemplateRequest;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class CropTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        // Fetch all crop templates with their grow stages
        $cropTemplates = CropTemplate::all();
        return CropTemplateResource::collection($cropTemplates);
    }

    /**
     * Store a newly created resource in storage.
     */    
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreCropTemplateRequest $request){
        // Validate the request
        $validated = $request->validated();

        // Create a new crop template
        $cropTemplate = CropTemplate::create($validated);

        // Handle thumbnail upload if provided
        if ($request->hasFile('thumbnail')) {
            // Handle the image upload
            $file = $request->file('thumbnail');
            $templateName = Str::slug($cropTemplate->name);
            $ext = $file->getClientOriginalExtension();
            $fileName = "{$templateName}.{$ext}";
            $path = "templates/{$cropTemplate->name}/{$fileName}";

            Storage::disk('public')->put($path, file_get_contents($file));
            
            $cropTemplate->update(['thumbnail' => $path]);
        }
        

        return new CropTemplateResource($cropTemplate);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        // Fetch a single crop template with its grow stages
        $cropTemplate = CropTemplate::with('growStage')->findOrFail($id);

        return CropTemplateResource::make($cropTemplate);
    }  
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(UpdateCropTemplateRequest $request, string $id){
        // Validate the request
        $validated = $request->validated();

        $cropTemplate = CropTemplate::findOrFail($id);

        if ($request->hasFile('thumbnail')) {
            // Handle the image upload
            $file = $request->file('thumbnail');
            $templateName = Str::slug($cropTemplate->name);
            $ext = $file->getClientOriginalExtension();
            $fileName = "{$templateName}.{$ext}";
            $path = "templates/{$cropTemplate->name}/{$fileName}";

            Storage::disk('public')->put($path, file_get_contents($file));

            $cropTemplate->update(['thumbnail' => $path]);
        }

        return new CropTemplateResource($cropTemplate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        // Find and delete the crop template
        $cropTemplate = CropTemplate::findOrFail($id);
        $cropTemplate->delete();

        return response()->json(['message' => 'Crop template deleted successfully.'], 200);
    }

    /**
     * Display a listing of the resource with stages.
     */
    public function templateWithStages(){
        // Fetch all crop templates with their grow stages
        $cropTemplates = CropTemplate::with('growStage')->get();
        return CropTemplateResource::collection($cropTemplates);
    }
}
