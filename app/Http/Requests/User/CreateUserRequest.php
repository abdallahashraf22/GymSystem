<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{

    public static function getRules()
    {
        return [
            "name" => ["required"],
            "email" => ["required", "unique:users,email"],
            "password" => ["required"],
            "national_id" => ["required", "unique:users,national_id"],
            "image_url" => ["required"]
        ];
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
        return CreateUserRequest::getRules();
    }
}
