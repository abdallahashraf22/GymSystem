<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public function __construct($user)
    {
            $this->user =  $user;
    }


    public function build()
    {
        return $this->view('welcome', ["user"=> $this->user]);
    }
}
