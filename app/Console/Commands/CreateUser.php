<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:newuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //get user's email

        $email = $this->ask("Enter New User Email");

        $password = $this->ask("Enter Password");

        $role = $this->ask("Please Select Role \n 1.admin \n 2.editor");

        $ROLES = ['admin', 'editor'];

        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            'role' => $role
        ], [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:1,2'
        ]);

        if ($validator->fails()) {

            $this->info('Validation errors:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
        } else {


            try {
                DB::beginTransaction();

                $users = User::create([
                    "email" => $email,
                    "password" => $password
                ]);

                $role_uuid = Role::where("name", $ROLES[$role])->pluck("id")->first();

                $users->roles()->attach([$role_uuid]);

                DB::commit();
                $this->info("User Created Successfully");
            } catch (\Exception $e) {
                DB::rollback();
                $this->error("User Creating failed: " . $e->getMessage() . $e->getLine());
            }
        }
    }
}
