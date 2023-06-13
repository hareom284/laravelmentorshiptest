<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                "name" => "admin"
            ],
            [
                "name" => "editor"
            ]
        ];

        foreach($roles as $role)
        {
            Role::create($role);
        }
    }
}
