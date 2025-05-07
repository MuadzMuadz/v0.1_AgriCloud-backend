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
}
