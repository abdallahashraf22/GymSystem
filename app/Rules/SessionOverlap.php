<?php

namespace App\Rules;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Contracts\Validation\Rule;

class SessionOverlap implements Rule
{

    public $message = 'Overlapping with another session, try a different start/end times';
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

        $sessions = Session::where("start_time", "<=", $value)->where('end_time', '>=', $value)->with('coaches')->get();
        foreach ($sessions as $session){
            if($session['branch_id'] == request('branch_id')){
                if($session['id'] == request('id')){
                    continue;
                }
                $this->message = 'Overlapping with another session, try a different start/end times';
                return false;
            }
            foreach ($session['coaches'] as $coach){
                foreach(request('coaches') as $inputcoach){
                    if($inputcoach == $coach['id']){
                        if($session['id'] == request('id')){
                            continue;
                        }
                        $this->message = "This Coach is busy, try another coach";
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     *
     *
     *
     *
     *
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
