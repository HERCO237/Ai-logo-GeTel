<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    public function messages(): array{
        return[
            'email.required' =>'Email is required',
            'email.email' =>'Email is not valid',
            'otp.required' =>'OTP is required',
            'otp.digits' =>'OTP must be 6 digits',
            'password.required' =>'Password is required',
            'password.confirmed' =>'Password confirmation does not match',
            'password.min' =>'Password must be at least 8 characters',
        ];
    }
}
