<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class CompanySectionTest extends TestCase
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
        $response = $this->get('dashboard/companies');

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
        $response = $this->get('/dashboard/companies/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreCompany()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $country = Country::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create();
        $faker = Faker::create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'company_name' => $faker->company,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->unique()->phoneNumber,
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'street' => $faker->streetAddress,
            'postal_code' => $faker->postcode,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/companies/store',$formData);

        #return
        $response->assertStatus(302);
    }

    public function testGoToEdit()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $company = Company::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/companies/edit/'.$company->id);

        #return 200
        $response->assertStatus(200);
    }

    public function testUpdateCompany()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $company = Company::factory()->create();
         $country = Country::factory()->create();
         $state = State::factory()->create();
         $city = City::factory()->create();
         $faker = Faker::create();
 
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         $formData = [
            'company_name' => $faker->company,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->unique()->phoneNumber,
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'street' => $faker->streetAddress,
            'postal_code' => $faker->postcode,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/companies/update/'.$company->id,$formData);

        $response->assertStatus(302);
    }

    public function testSoftDeletedCompanies()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $company = Company::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/companies/softDelete/'.$company->id);

        #return 200
        $response->assertStatus(302);
    }

    public function testGoToSoftDeletedCompanies()
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
        $response = $this->get('/dashboard/companies/showSoftDelete');

        #return 200
        $response->assertStatus(200);
    }

    public function testRestoreSoftDelete()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $company = Company::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/companies/softDeleteRestore/'.$company->id);

        #return 200
        $response->assertStatus(302);
    }

}
