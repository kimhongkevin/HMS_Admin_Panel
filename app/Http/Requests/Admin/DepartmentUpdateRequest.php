<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $departmentId = $this->route('department')->id;

        return [
            // Validation Rules[cite: 5]:
            // Unique rules must ignore the current department ID
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($departmentId)],
            'code' => ['required', 'string', 'max:50', 'uppercase', Rule::unique('departments', 'code')->ignore($departmentId)],
            'description' => ['nullable', 'string'],
            'head_doctor_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'doctor');
                }),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}