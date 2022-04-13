<?php

namespace App\Http\Requests\User;

use App\Http\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["required"],
            "email" => ["required", "unique:users,email"],
            "password" => ["required"],
            "national_id" => ["required", "unique:users,national_id"],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = $this->createResponse(200, [], false, $validator->errors());

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
