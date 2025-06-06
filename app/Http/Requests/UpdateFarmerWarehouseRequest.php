<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFarmerWarehouseRequest extends FormRequest
{    
    /**
     * authorize
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Check if the user is authenticated and has the 'farmer' role
        // return auth()->guard()->check() && auth()->guard()->user()->role === 'farmer';
        // Check if the user is authenticated and has the 'farmer' role

        return auth()->guard()->check() && auth()->guard()->user()->role === 'farmer';
    }
    
    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:farmer_warehouses,name,' . $this->route('id'),
            'location_url' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
