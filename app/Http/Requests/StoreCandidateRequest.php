<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:candidates,email,' . ($this->candidate ? $this->candidate->id : ''),
            'phone' => 'nullable|string|max:20',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'current_position' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:new,contacted,interviewing,offered,hired,rejected'
        ];
    }
}
