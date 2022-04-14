<?php

namespace App\Http\Requests;

use App\Http\Traits\ResponseTrait;
use App\Rules\TodaySession;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
        $user_id = request('user_id');
        $session_id = request('session_id');
//        dd($something);
        return [
            'user_id'=>['required', "unique:user_session,user_id,NULL,NULL,session_id,$session_id"],
            'session_id' => ['required', "unique:user_session,session_id,NULL,NULL,user_id,$user_id", new TodaySession()]
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = $this->createResponse(200, [], false, $validator->errors());

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
