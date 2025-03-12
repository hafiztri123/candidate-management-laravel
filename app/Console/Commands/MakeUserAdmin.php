<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email : The email of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns the admin role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found");
            return 1;
        }

        $adminRole = Role::where('slug', 'admin')->first();
        if (!$adminRole){
            $this->error('Admin role not found. Please run the role seeder');
            return 1;
        }

        $user->roles()->detach();
        $user->roles()->attach($adminRole);

        $this->info("User {$user->name} ({$email}) is now an admin");
        return 0;
    }
}
