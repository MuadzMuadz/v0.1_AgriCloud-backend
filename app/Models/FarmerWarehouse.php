<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
// use App\Models\Field;

class FarmerWarehouse extends Model
{
    /** @use HasFactory<\Database\Factories\FarmerWarehouseFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'location_url'
    ];

    /**
     * Get all of the user for the User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * Get all of the field f FarmerWarehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->hasMany(Field::class, 'farmer_warehouse_id', 'id');
    }

    /**
     * Get all of the photos for the FarmerWarehouse
     */
    public function photos()
    {
        return $this->hasMany(WarehouseImage::class, 'farmer_warehouse_id', 'id');
    }
}
