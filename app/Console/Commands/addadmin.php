<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class addadmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user, arguments are make:admin" name email password';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            User::create([
                'name' => $this->argument("name"),
                'email' => $this->argument("email"),
                'isbanned' => false,
                'password' => bcrypt($this->argument("password")),
                'national_id' => uniqid(),
                'role' => "admin",
                'image_url' => "assets/images/noImageYet.jpg",
            ]);
        } catch (\Exception $e) {

            return $this->createResponse(500, [], false, "server error");
        }
    }
}
