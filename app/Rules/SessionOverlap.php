<?php

namespace App\Rules;

use App\Models\Session;
use Illuminate\Contracts\Validation\Rule;

class SessionOverlap implements Rule
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
        return Session::where("start_time", "<=", $value)->where('end_time', '>=', $value)->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Overlapping with another session, try a different start/end times';
    }
}
