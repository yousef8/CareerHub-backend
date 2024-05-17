<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|string|min:8',
            'phone_number' => ['sometimes', 'string', 'regex:/^01[0125][0-9]{8}$/'],
            'profile_image' => 'sometimes|image',
            'cover_image' => 'sometimes|image',
            'role' => 'prohibited'
        ];
    }
}
