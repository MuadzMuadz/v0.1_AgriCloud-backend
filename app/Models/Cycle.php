<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CropTemplate;
use App\Models\CycleStages;
use App\Models\Field;
use Carbon\Carbon;

use function Laravel\Prompts\progress;

class Cycle extends Model
{
    /** @use HasFactory<\Database\Factories\CycleFactory> */
    use HasFactory;

    protected $fillable =[
        'field_id',
        'crop_template_id',
        'start_date',
        'status',
        'progress',
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

    public function refreshStatusIfNeeded(): void
    {
        $today = now();
        $start = Carbon::parse($this->start_date);
        $lastStage = $this->cycleStage->sortByDesc('day_offset')->first();

        $status = $this->status;

        if ($today->lt($start)) {
            $status = 'pending';
        } elseif ($lastStage && $today->gt($lastStage->start_at)) {
            $status = 'completed';
        } else {
            $status = 'active';
        }

        if ($this->status !== $status) {
            $this->status = $status;
            $this->save();
        }
    }

    public function getProgressAttribute(): int
    {
        $startDate = Carbon::parse($this->start_date);
        $now = Carbon::now();

        $daysPassed = $startDate->diffInDays($now);

        $totalDays = $this->cycleStage()->sum('day_offset');

        if ($totalDays === 0) return 0;

        $progress = ($daysPassed / $totalDays) * 100;

        return min(100, round($progress)); // Jangan lebih dari 100
    }
}
