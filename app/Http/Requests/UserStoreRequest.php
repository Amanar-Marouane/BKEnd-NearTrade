<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:users,name', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', Password::default(), 'confirmed'],
            'profile' => ['required', 'mimes:jpeg,jpg,png'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.unique' => 'This name is already taken. Please choose another one.',

            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',

            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a valid string.',

            'profile.mimes' => 'Only jpeg, jpg, and png image types are allowed for the profile.',
        ];
    }
}
