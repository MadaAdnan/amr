<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Spatie\Permission\Models\Role;


class UserSectionDashboardTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGoToAdminIndex()
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
        $response = $this->get('dashboard/admins');

        #return 200
        $response->assertStatus(200);
    }

    public function testGoToAdminCreate()
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
         $response = $this->get('dashboard/admins/create');
 
         #return 200
         $response->assertStatus(200);
    }

    public function testGoToAdminEdit()
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
         $response = $this->get('dashboard/admins/edit/'.$user->id);
 
         #return 200
         $response->assertStatus(200);
    }

    public function testStoringAdminUser()
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
            'first_name'=>'Ahmed',
            'last_name'=>'Khalid',
            'email'=>'ahmed@gmail.com',
            'role_id'=>'1',
            'phone'=>'0775429329'
        ];

        // Simulate a form request
        $response = $this->post('/dashboard/admins/store', $formData);

        // Assert the response, for example:
        $response->assertStatus(302);
    }

    public function testGeneratePasswordAdminUser()
    {

         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
 
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 

        // Simulate a form request
        $response = $this->post('/dashboard/admins/generatePassword/'.$user->id);

        // Assert the response, for example:
        $response->assertStatus(302);
    }

    public function testChangeAdminStatus()
    {

         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
 
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 

        // Simulate a form request
        $response = $this->post('/dashboard/admins/change_status/'.$user->id);

        // Assert the response, for example:
        $response->assertStatus(200);
    }

    public function testDeleteAdmin()
    {

         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
 
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 

        // Simulate a form request
        $response = $this->post('/dashboard/admins/delete/'.$user->id);

        // Assert the response, for example:
        $response->assertStatus(204);
    }

    public function testGoToUpdateProfileAdmin()
    {

         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
 
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 

        // Simulate a form request
        $response = $this->get('/dashboard/admins/update_profile');

        // Assert the response, for example:
        $response->assertStatus(200);
    }
}
