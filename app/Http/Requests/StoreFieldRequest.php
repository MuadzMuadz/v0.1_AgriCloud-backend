<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Check if the user is authenticated and has the 'farmer' role
        return auth()->guard()->user() && auth()->guard()->user()->role === 'farmer';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255', Rule::unique('fields')->where(function ($query) {
                return $query->where('user_id', auth()->guard()->id());
            })],
            'description' => 'nullable|string|max:1000',
            'area' => 'required|numeric|min:0.1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'custom_polygon' => 'nullable|json'
        ];
    }
}
