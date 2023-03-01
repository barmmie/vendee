<?php

namespace App\Http\Requests;

use App\Models\Enum\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'min:3', Rule::unique('users', 'username')],
            'password' => ['required', 'confirmed'],
            'role' => ['required', Rule::in(Role::values())]
        ];
    }
}
