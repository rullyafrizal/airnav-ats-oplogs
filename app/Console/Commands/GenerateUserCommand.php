<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new user by inserting email aand password';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating new user...');

        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        User::query()
            ->create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);

        $this->info('User created successfully!');
    }
}
