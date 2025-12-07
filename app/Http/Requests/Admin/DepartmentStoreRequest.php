<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by the 'role:admin' middleware in the controller 
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Validation Rules[cite: 5]:
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')],
            'code' => ['required', 'string', 'max:50', 'uppercase', Rule::unique('departments', 'code')],
            'description' => ['nullable', 'string'],
            'head_doctor_id' => [
                'nullable',
                'integer',
                // Ensure the ID exists in the 'users' table AND the user has the 'doctor' role
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'doctor');
                }),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}