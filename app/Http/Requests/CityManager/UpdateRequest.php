<?php

namespace App\Http\Requests\CityManager;

use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
   use ResponseTrait;
    public function authorize()
    {
        return true;
    }


    public function rules(User $user)
    {
        logger($user);
        return [
            "name" => ["required"],
//            "email" => ["required", "unique:users,email, {$this->id}, id"],
//            'email' => 'required|email|unique:users,email,'.$this->id,
//            'email' => Rule::unique('users')->ignore($this->id),//use it in PUT or PATCH method
//        "national_id" => ["required", "unique:users,national_id, {$this->id}, id"],
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->id),
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->createResponse(200, [], false, $validator->errors());
        logger(request()->all());
        throw new ValidationException($validator, $response);
    }
}
