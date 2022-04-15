<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Mail\TestMail;

class Email_Each_Non_Logged_Month extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:non_logged_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "This command sends remainder emails to users who haven't logged in for a month";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $users = User::get()->where('last_login', '<=', now()->subDays(30)->endOfDay())->all();
            foreach ($users as $user){
                \Illuminate\Support\Facades\Mail::send(new TestMail($user->email));
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 0;
    }
}
