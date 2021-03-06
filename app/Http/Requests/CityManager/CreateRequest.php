<?php

namespace App\Http\Requests\CityManager;

use App\Http\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class CreateRequest extends FormRequest
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
            "city_id" => ["required"]
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = $this->createResponse(200, [], false, $validator->errors());
        throw new ValidationException($validator, $response);
    }
}
