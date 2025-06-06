<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFarmerWarehouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */    
    /**
     * authorize
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Check if the user is authenticated and has the 'farmer' role
        return auth()->guard()->check() && auth()->guard()->user()->role === 'farmer';
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:farmer_warehouses,name',
            'location_url' => 'required|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama gudang wajib diisi.',
            'name.unique' => 'Nama gudang sudah digunakan.',
            'location_url.required' => 'Link lokasi wajib diisi.',
            'photos.*.image' => 'Setiap file harus berupa gambar.',
            'photos.*.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
