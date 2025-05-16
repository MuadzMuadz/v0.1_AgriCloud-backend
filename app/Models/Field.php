<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FarmerWarehouse;
use App\Models\Cycle;

class Field extends Model
{
    /** @use HasFactory<\Database\Factories\FieldFactory> */
    use HasFactory;

    protected $fillable = [
        'farmer_warehouse_id',
        'name',
        'area'
    ];

    /**
     * Get the farmer_warehouse owns the fields
     */
    public function farmerwarehouse()
    {
        return $this->belongsTo(FarmerWarehouse::class, 'farmer_warehouse_id', 'id');
    }
    /**
     * Get all of the cycle for the Field
     */
    public function cycle()
    {
        return $this->hasMany(Cycle::class, 'field_id', 'id');
    }
}
