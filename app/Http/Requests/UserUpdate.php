<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'sometimes|string|email|max:255|unique:users,email',
            'phone_number' => 'sometimes|string|max:100',
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:admin,farmer',
            'profile_photo' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:2048'
        ];
    }
}
