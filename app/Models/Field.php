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
        'user_id',
        'name',
        'area',
        'latitude',
        'longitude',
        'custom_polygon',
    ];
    
    protected $casts = [
        'custom_polygon' => 'array'
    ];

    /**
     * Get the farmer_warehouse owns the fields
     */
    public function user()
    {
        return $this->belongsTo(FarmerWarehouse::class, 'fuser_id', 'id');
    }
    /**
     * Get all of the cycle for the Field
     */
    public function cycle()
    {
        return $this->hasMany(Cycle::class, 'field_id', 'id');
    }
}
