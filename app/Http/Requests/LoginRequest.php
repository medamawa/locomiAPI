<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'messages' => $validator->errors(),
        ], 400);

        throw new HttpResponseException($response);
    }
}
