<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AirlineSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AppSettingSeeder::class);
        $this->call(AttachCustomerRoleSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(NavPageSeeder::class);
        $this->call(MakeUsersCustomerSeeder::class);
    }
}
