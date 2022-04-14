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
            "name" => "required",
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->id),
            ],
            'national_id' => [
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
