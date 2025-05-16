<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowStages extends Model
{
    /** @use HasFactory<\Database\Factories\GrowStagesFactory> */
    use HasFactory;

    protected $fillable =[
        'crop_template_id',
        'stage_name',
        'day_offset',
        'expected_action',
        'description',
    ];    
     /**
     * Get the farmer_warehouse owns the fields
     */
    public function cropTemplate()
    {
        return $this->belongsTo(CropTemplate::class, 'crop_template_id', 'id');
    }
}
