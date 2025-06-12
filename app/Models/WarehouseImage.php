<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseImage extends Model
{
    /** @use HasFactory<\Database\Factories\WarehouseImageFactory> */
    use HasFactory;

    protected $fillable = [
        'farmer_warehouse_id',
        'photo_path',
    ];
    /**
     * Get the warehouse that owns the image.
     */
    public function warehouse()
    {
        return $this->belongsTo(FarmerWarehouse::class, 'warehouse_id', 'id');
    }    
}
