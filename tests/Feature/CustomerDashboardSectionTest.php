<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerDashboardSectionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use DatabaseTransactions;

    public function testGoToCustomersIndex()
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
       $response = $this->get('dashboard/customers');

       #return 200
       $response->assertStatus(200);
    }
}
