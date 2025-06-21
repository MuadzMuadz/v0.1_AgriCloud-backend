<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCycleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'crop_template_id' => 'required|integer|exists:crop_templates,id',
            'field_id' => 'required|integer |exists:fields,id',
            'start_date' => 'required|date',
            'status' => 'nullable|in:pending, started, active,completed',
        ];
    }
}
