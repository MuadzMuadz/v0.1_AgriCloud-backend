<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:100',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,farmer',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ];
    }
}
