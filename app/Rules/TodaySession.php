<?php

namespace App\Rules;

use App\Models\Session;
use Illuminate\Contracts\Validation\Rule;

class TodaySession implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $session = Session::find($value);
        if($session){
            if($session->start_time >= today() && $session->start_time < today()->addDay(1)){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You can only record your attendance on the same day as the session.';
    }
}
