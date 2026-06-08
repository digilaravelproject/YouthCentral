<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {--name= : The name of the admin user} {--email= : The email of the admin user} {--password= : The password of the admin user} {--phone= : The phone number of the admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('Enter admin name');
        $email = $this->option('email') ?? $this->ask('Enter admin email');
        $password = $this->option('password') ?? $this->secret('Enter admin password (min 8 characters)');
        $phone = $this->option('phone') ?? $this->ask('Enter admin phone number (optional)');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Create the admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone,
            'role' => 'admin',
            'status' => 'approved',
        ]);

        $this->info('Admin user created successfully:');
        $this->table(
            ['Name', 'Email', 'Role'],
            [[$user->name, $user->email, $user->role]]
        );

        return 0;
    }
}
