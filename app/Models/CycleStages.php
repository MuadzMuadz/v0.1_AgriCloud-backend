<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CycleStages extends Model
{
    /** @use HasFactory<\Database\Factories\CycleStagesFactory> */
    use HasFactory;

    protected $fillable =[
        'cycle_id',
        'stage_name',
        'day_offset',
        'expected_action',
        'description',
        'started_at',
    ];

     /**
     * Get the farmer_warehouse owns the fields
     */
    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'cycle_id', 'id');
    }
}
