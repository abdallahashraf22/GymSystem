<?php

namespace App\Http\Requests\User;

use App\Http\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            "email" => ["required", "unique:users,email,{$this->id}"],
            "national_id" => ["required", "unique:users,national_id,{$this->id}"],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = $this->createResponse(200, [], false, $validator->errors());

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
