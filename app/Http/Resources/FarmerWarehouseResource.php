<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Http\Resources\UserResource;

class FarmerWarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'owner' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'phone number' => $this->user->phone_number,
                'role' => $this->user->role,
                ],
            'photos' => $this->WarehouseImage->map(function ($photo) {
                return asset('storage/' . $photo->path);
            }),
            'location_url' => $this->location_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
    