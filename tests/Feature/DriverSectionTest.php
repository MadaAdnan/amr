<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class DriverSectionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseTransactions;

    public function testGoToIndex()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('dashboard/logs/userLogs');

        #return 200
        $response->assertStatus(200);
    }

    public function testGoToCreate()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/admins/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreDriver()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
        'first_name'=> 'Kareem',
        'last_name' =>'Cameron',
        'email' => $faker->unique()->safeEmail,
        'role_id' => 3,
        'phone' =>' +1 (977) 718-9731'
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/admins/store',$formData);


        #return
        $response->assertStatus(302);
    }
    
    public function testGoToEditDriver()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         #Access the user dashboard
         $response = $this->get('/dashboard/admins/edit/'.$user->id,);
 
         #return
         $response->assertStatus(200);
    }

    public function testUpdateDriver()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         $formData = [
            'first_name'=> 'Kareem',
            'last_name' =>'Cameron',
            'email' => 'suhedacy@mailinator.com',
            'role_id' => 3,
            'phone' =>' +1 (977) 718-9731'
            ];
    
            #Access the user dashboard
            $response = $this->post('/dashboard/admins/update/'.$user->id,$formData);

        $response->assertStatus(302);
    }

    public function testResetPassword()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         $formData = [
            'first_name'=> 'Kareem',
            'last_name' =>'Cameron',
            'email' => 'suhedacy@mailinator.com',
            'role_id' => 3,
            'phone' =>' +1 (977) 718-9731'
            ];
    
            #Access the user dashboard
            $response = $this->post('/dashboard/admins/generatePassword/'.$user->id,$formData);

        $response->assertStatus(302);
    }


}
