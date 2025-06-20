<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGrowStagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard()->check() && auth()->guard()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'crop_template_id' => 'required|exists:crop_templates,id',
            'stage_name' => 'required|string|max:255',
            'day_offset' => 'required|integer|min:0',
            'expected_action' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
