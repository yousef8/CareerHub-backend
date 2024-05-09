<?php

namespace App\Http\Requests;
use App\Enums\ServerStatus;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'resume_path' =>  'required|mimes:pdf',
            'status' => [Rule::enum(ServerStatus::class)]
        ];
    }
}
