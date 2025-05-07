<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CropTemplate;
use App\Models\CycleStages;

class Cycle extends Model
{
    /** @use HasFactory<\Database\Factories\CycleFactory> */
    use HasFactory;

    protected $fillable =[
        'field_id',
        'crop_template_id',
        'start_date',
        'status',
    ];
    /**
     * Get the farmer_warehouse owns the fields
     */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', 'id');
    }
    public function cropTemplate()
    {
        return $this->belongsTo(CropTemplate::class, 'crop_template_id', 'id');
    }
    /**
     * Get all of the cycle for the Field
     */
    public function cycleStage()
    {
        return $this->hasMany(CycleStages::class, 'cycle_id', 'id');
    }
}
