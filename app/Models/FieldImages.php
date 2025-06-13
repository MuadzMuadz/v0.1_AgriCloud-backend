<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Field;

class FieldImages extends Model
{
    use HasFactory;
    /** @use HasFactory<\Database\Factories\FieldImagesFactory> */
    /** @var string */      
    protected $table = 'field_images';

    protected $fillable = [
        'field_id',
        'image_path',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : null;
    }
}
