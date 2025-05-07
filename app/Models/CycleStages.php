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
        'grow_stage_id',
        'started_at',
    ];

     /**
     * Get the farmer_warehouse owns the fields
     */
    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'cycle_id', 'id');
    }
    public function growStage()
    {
        return $this->belongsTo(GrowStages::class, 'grow_stage_id', 'id');
    }
}
