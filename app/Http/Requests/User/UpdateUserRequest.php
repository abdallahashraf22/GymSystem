<?php

namespace App\Http\Requests\User;

use App\Http\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    use ResponseTrait;

    public static function getRules()
    {
        // return [
        //     "name" => ["required"],
        //     "email" => ["required", "unique:users,email,{$this->id}"],
        //     "password" => ["required"],
        //     "national_id" => ["required", "unique:users,national_id,{$this->id}"],
        // ];
    }
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
            "email" => ["required", "unique:users,email,{$this->id}"],
            "password" => ["required"],
            "national_id" => ["required", "unique:users,national_id,{$this->id}"],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = $this->createResponse(400, [], false, $validator->errors());

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
