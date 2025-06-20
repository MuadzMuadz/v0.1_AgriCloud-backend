<?php

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Fetch data for fields, warehouses, and cycles related to the user
        $fields = Field::whereHas('farmerWarehouse', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get(); // Fetch fields associated with farmer warehouses linked to the user

        // $warehouses = FarmerWarehouse::where('user_id', $user->id)->get(); // Fetch warehouses directly linked to the user

        $cycles = Cycle::whereHas('field.user', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('stages')->get(); // Fetch cycles associated with fields linked to farmer warehouses of the user

        // Prepare the response data
        return response()->json([
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'fields' => $fields, // List of fields to be displayed in a table
                // 'warehouses' => $warehouses, // List of warehouses to be displayed in a table
                'cycles' => $cycles->map(function ($cycle) {
                    // Map the cycle data to include only necessary fields
                    // and the stages associated with each cycle
                    return [
                        'id' => $cycle->id,
                        'name' => $cycle->name,
                        'description' => $cycle->description,
                        'stages' => $cycle->stages, // List of cycle stages
                    ];
                }), // List of cycles to be displayed in swipeable cards
            ],
        ]);
    }
}
