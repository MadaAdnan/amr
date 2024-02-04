<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('name','Super-admin')->first();
        $user = User::where('email','y.mansour@lavishride.com')->first();

        if($user)
        {
            $user->assignRole($admin);
        }
    }
}
