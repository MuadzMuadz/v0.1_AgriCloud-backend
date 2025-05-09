<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cycle;
use App\Models\GrowStages;

class CropTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\CropTemplateFactory> */
    use HasFactory;

    protected $fillable =[
        'name',
        'description',       
    ];

    /**
     * Get all of the cycle f CropTemplate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cycle()
    {
        return $this->hasMany(Cycle::class, 'crop_template_id', 'id');
    }
    public function growStage()
    {
        return $this->hasMany(GrowStages::class, 'crop_template_id', 'id');
    }
}
