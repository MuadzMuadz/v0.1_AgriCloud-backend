<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
            // 'user_id' => $this->user_id,
            'name' => $this->name,
            'owner' => [
                'id' => $this->user_id,
                // 'name' => $this->user->name,
                // 'email' => $this->user->email,
                // 'phone_number' => $this->user->phone_number,
                // 'role' => $this->user->role,
                ],
            'location_url' => $this->location_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
    