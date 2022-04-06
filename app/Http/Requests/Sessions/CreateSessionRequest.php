<?php

namespace App\Http\Requests\Sessions;

use App\Http\Traits\ResponseTrait;
use App\Rules\SessionOverlap;
use Illuminate\Foundation\Http\FormRequest;

class CreateSessionRequest extends FormRequest
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
            'name' => "required",
            'branch_id' => "required",
            'start_time'=> [new SessionOverlap(), "required"],
            'end_time' => [new SessionOverlap(), "required"],
            'coaches' => "required"
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = $this->createResponse(200, [], false, $validator->errors());

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
