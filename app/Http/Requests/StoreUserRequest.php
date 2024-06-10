<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
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
            "firstName" => "required",
            "lastName" => "required",
            "userName" => "required",
            "email" => "required",
            "password" => "required",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "firstName.required" => "The user's first name is required",
            "lastName.required" => "The user's last name is required",
            "userName.required" => "The user's user name is required",
            "email.required" => "The user's email id is required",
            "password.required" => "The user's password is required",
        ];
    }

    public function failedValidation(Validator $validator) {

        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $errors,
        ], 422));

    }
}
