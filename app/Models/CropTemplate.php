<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\CropTemplateFactory> */
    use HasFactory;

    protected $fillable =[
        'name',
        'description',       
    ];
}
