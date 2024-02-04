<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\FleetCategory;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CitySectionTest extends TestCase
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
        $state = State::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('dashboard/countries/cities/'.$state->id);

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
        $response = $this->get('/dashboard/cities/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreCity()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $city = City::factory()->create();
        $fleetCategory = FleetCategory::factory()->create();
        $faker = Faker::create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'status'=>City::STATUS_ACTIVE,
            'daily_from' => $faker->time,
            'daily_to' => $faker->time,
            'daily_price' => $faker->randomFloat(2, 10, 100),
            'updated_at' => now(),
            'city_id' => $city->id,
            'pointToPoint' => [
                [
                    'initial_fee' => $faker->randomFloat(2, 1, 100),
                    'minimum_price' => $faker->randomFloat(2, 1, 100),
                    'minimum_mile' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_mile' => $faker->randomFloat(2, 1, 100),
                    'minimum_mile_hour' => $faker->randomFloat(2, 1, 100),
                    'price_per_mile_hour' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_hour' => $faker->randomFloat(2, 1, 100),
                    'price_per_hour' => $faker->randomFloat(2, 1, 100),
                    'minimum_hour' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_mile_hourly' => $faker->randomFloat(2, 1, 100),
                    'city_id' => $city->id,
                    'service_id' => 1,
                    'category_id' => $fleetCategory->id,
                ],
            ],
            'hourly' => [
                [
                    'initial_fee' => $faker->randomFloat(2, 1, 100),
                    'minimum_price' => $faker->randomFloat(2, 1, 100),
                    'minimum_mile' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_mile' => $faker->randomFloat(2, 1, 100),
                    'minimum_mile_hour' => $faker->randomFloat(2, 1, 100),
                    'price_per_mile_hour' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_hour' => $faker->randomFloat(2, 1, 100),
                    'price_per_hour' => $faker->randomFloat(2, 1, 100),
                    'minimum_hour' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_mile_hourly' => $faker->randomFloat(2, 1, 100),
                    'city_id' => $city->id,
                    'service_id' => 2,
                    'category_id' => $fleetCategory->id,
                ],
            ],        
            'periodTwentyfour'=>$faker->randomFloat(2, 1, 100),
            'chargeTwentyfour'=>$faker->randomFloat(2, 1, 100),
            'periodNineteen'=>$faker->randomFloat(2, 1, 100),
            'chargeNineteen'=>$faker->randomFloat(2, 1, 100),
            'periodTwelve'=>$faker->randomFloat(2, 1, 100),
            'chargeTwelve'=>$faker->randomFloat(2, 1, 100),
            'periodSix'=>$faker->randomFloat(2, 1, 100),
            'chargeSix'=>$faker->randomFloat(2, 1, 100),
            'fleet_category[0]'=>$fleetCategory->id,
            'title'=>$city->name
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/cities/store',$formData);

        #return
        $response->assertStatus(200);
    }

    public function testGoToEdit()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $city = City::factory()->create();
        $fleetCategory = FleetCategory::factory()->create();
        $city->fleets_category()->sync([$fleetCategory->id]);
        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/cities/edit/'.$city->id);

        #return 200
        $response->assertStatus(200);
    }

    public function testUpdateCity()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $city = City::factory()->create();
         $fleetCategory = FleetCategory::factory()->create();
         $faker = Faker::create();
 
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
         $formData = [
            'status'=>City::STATUS_ACTIVE,
            'daily_from' => $faker->time,
            'daily_to' => $faker->time,
            'daily_price' => $faker->randomFloat(2, 10, 100),
            'updated_at' => now(),
            'city_id' => $city->id,
            'pointToPoint' => [
                [
                    'initial_fee' => $faker->randomFloat(2, 1, 100),
                    'minimum_price' => $faker->randomFloat(2, 1, 100),
                    'minimum_mile' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_mile' => $faker->randomFloat(2, 1, 100),
                    'minimum_mile_hour' => $faker->randomFloat(2, 1, 100),
                    'price_per_mile_hour' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_hour' => $faker->randomFloat(2, 1, 100),
                    'price_per_hour' => $faker->randomFloat(2, 1, 100),
                    'minimum_hour' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_mile_hourly' => $faker->randomFloat(2, 1, 100),
                    'city_id' => $city->id,
                    'service_id' => 1,
                    'category_id' => $fleetCategory->id,
                ],
            ],
            'hourly' => [
                [
                    'initial_fee' => $faker->randomFloat(2, 1, 100),
                    'minimum_price' => $faker->randomFloat(2, 1, 100),
                    'minimum_mile' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_mile' => $faker->randomFloat(2, 1, 100),
                    'minimum_mile_hour' => $faker->randomFloat(2, 1, 100),
                    'price_per_mile_hour' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_hour' => $faker->randomFloat(2, 1, 100),
                    'price_per_hour' => $faker->randomFloat(2, 1, 100),
                    'minimum_hour' => $faker->randomFloat(2, 1, 100),
                    'extra_price_per_mile_hourly' => $faker->randomFloat(2, 1, 100),
                    'city_id' => $city->id,
                    'service_id' => 1,
                    'category_id' => $fleetCategory->id,
                ],
            ],        
            'periodTwentyfour'=>$faker->randomFloat(2, 1, 100),
            'chargeTwentyfour'=>$faker->randomFloat(2, 1, 100),
            'periodNineteen'=>$faker->randomFloat(2, 1, 100),
            'chargeNineteen'=>$faker->randomFloat(2, 1, 100),
            'periodTwelve'=>$faker->randomFloat(2, 1, 100),
            'chargeTwelve'=>$faker->randomFloat(2, 1, 100),
            'periodSix'=>$faker->randomFloat(2, 1, 100),
            'chargeSix'=>$faker->randomFloat(2, 1, 100),
            'fleet_category[0]'=>$fleetCategory->id,
            'title'=>$city->name
        ];

         #Access the user dashboard
         $response = $this->post('/dashboard/cities/update/'.$city->id,$formData);

         #return
         $response->assertStatus(200);
    }

    public function testGetCityAccordingToState()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $state = State::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/cities/accordingToState/'.$state->name);

        #return 200
        $response->assertStatus(200);
    }

    public function testGetCityInfo()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $city = City::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/cities/getCityInfo/'.$city->id);

        #return 200
        $response->assertStatus(200);
    }



}
