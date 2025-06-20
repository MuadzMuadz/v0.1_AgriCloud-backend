<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCycleRequest extends FormRequest
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
            'crop_template_id' => 'sometimes|integer|exists:crop_templates,id',
            'field_id' => 'sometimes|integer |exists:fields,id ',
            'crop_template_id' => 'sometimes|integer',
            'start_date' => 'sometimes|date',
            'status' => 'nullable|in:pending, started, active,completed',
        ];
    }
}
