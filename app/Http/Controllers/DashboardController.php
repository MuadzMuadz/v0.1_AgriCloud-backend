<?php

namespace App\Http\Controllers;

use App\Http\Resources\CycleResource;
use App\Http\Resources\FieldResource;
use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\FarmerWarehouse;
use App\Models\Cycle;
// use App\Models\CycleStage;
// use App\Models\CropTemplate;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function IndexField()
    {
        $user = auth()->guard()->user();
        $fields = Field::where('user_id', $user->id)->get();
        return FieldResource::collection($fields);
    }
    public function indexCycle()
    {
        $user = auth()->guard()->user();
        $fieldIds = Field::where('user_id', $user->id)->pluck('id');
        $cycles = Cycle::whereIn('field_id', $fieldIds)->get();
        return CycleResource::collection($cycles);
    }
}
