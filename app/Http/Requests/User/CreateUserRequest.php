<?php

namespace App\Http\Requests\User;

use App\Http\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CreateUserRequest extends FormRequest
{
    use ResponseTrait;


    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            "name" => ["required"],
            "email" => ["required", "unique:users,email"],
            "password" => ["required"],
            "national_id" => ["required", "unique:users,national_id"],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->createResponse(200, [], false, $validator->errors());
        throw new ValidationException($validator, $response);
    }
}
