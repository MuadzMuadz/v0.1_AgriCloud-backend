<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(auth()->guard()->id()),
            ],
            'phone_number' => 'sometimes|string|max:100',
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:admin,farmer',
            'profile_photo' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:2048'
        ];
    }
}
