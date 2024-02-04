<?php

namespace Tests\Feature;

use Tests\TestCase;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Country;
use Faker\Factory as Faker;

class CountriesSectionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
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
        $response = $this->get('dashboard/countries');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreCountry()
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
            'name' => $faker->name,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/countries/store',$formData);

        #return
        $response->assertStatus(200);
    }

    public function testGetStatesForCountries()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();
        $country = Country::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'name' => $faker->name,
        ];

        #Access the user dashboard
        $response = $this->get('/dashboard/countries/states/'.$country->id);

        #return
        $response->assertStatus(200);
    }

    public function testStoreStateForCountries()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();
        $country = Country::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'name' => $faker->name,
            'country_id'=> $country->id
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/states/store',$formData);

        #return
        $response->assertStatus(200);
    }
    

}
