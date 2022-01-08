<?php

namespace App\Console\Commands;

use App\Models\User;
use Auth;
use Illuminate\Console\Command;

class GenerateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

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
        $this->info('You have to login first');
        $em = $this->ask('Email ');
        $pass = $this->secret('Password ');

        if (!Auth::attempt(['email' => $em, 'password' => $pass])) {
            $this->error('Invalid credentials.');

            return 1;
        }

        $this->info('Generating new user...');

        $name = $this->ask('Name ');
        $email = $this->ask('Email ');
        $password = $this->secret('Password ');

        User::query()
            ->create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);

        $this->info('User created successfully!');
    }
}
