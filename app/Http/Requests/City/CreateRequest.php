<?php

namespace App\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{

    public function authorize()
    {
        return false;
    }


    public function rules()
    {
        return [
            "name" => ["required"],
        ];
    }
}
