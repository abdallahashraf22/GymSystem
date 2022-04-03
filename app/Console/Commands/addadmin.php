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
    protected $description = 'Create an admin user, arguments are "command" "name" "email" "password"';

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
                'national_id' => 0,
                'role' => "admin",
                'image_url' => 0,
            ]);
        } catch (\Exception $e) {

            return $this->createResponse(500, [], false, "server error");
        }
    }
}
