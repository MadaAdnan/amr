<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class MakeUsersCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        $role = Role::where('name','Customer')->first();

        foreach($users as $user)
        {
            $user->assignRole($role);
        }
    }
}
