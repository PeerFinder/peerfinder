<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AdminCreate extends Command
{
    private $min_pass_len = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage admins';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->min_pass_len = 8;
        parent::__construct();
    }

    private function validatePassword($password)
    {
        if (strlen($password) < $this->min_pass_len) {
            $this->error(sprintf('The password shall be at least %s charachters long!', $this->min_pass_len));
            return false;
        }

        return true;
    }    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $password = "";
        $existing_admin = Admin::where('email', $this->argument('email'))->first();

        if ($existing_admin) {
            return $this->error('Admin with this email is already in the database!');
        }

        $pwd_ok = false;

        while (!$pwd_ok) {
            $pwd_from_cli = $this->secret('Enter password');
            $pwd_conform_from_cli = $this->secret('Confirm password');

            if ($pwd_from_cli != $pwd_conform_from_cli) {
                $this->error('Passwords don\'t match!');
                continue;
            }
    
            if ($this->validatePassword($pwd_from_cli)) {
                $password = $pwd_from_cli;
            } else {
                continue;
            }

            $pwd_ok = true;
        }

        $admin = new Admin();
        $admin->password = Hash::make($password);
        $admin->email = $this->argument('email');
        $admin->name = $this->argument('name');
        $admin->save();

        return $this->info('New admin created!');
    }
}
