<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class DeleteTodoRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;


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
            "uuid" => "required|exists:todos,uuid",
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
            "uuid.required" => "The uuid for todo is required",
            "uuid.exists" => "The uuid for todo does not exist",
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

