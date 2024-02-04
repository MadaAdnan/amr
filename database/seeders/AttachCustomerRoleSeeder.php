<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AttachCustomerRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = Role::where('name','Customer')->first();
        $users = User::get();

        foreach($users as $user)
        {
            $user->assignRole($customer);
        }

    }
}
