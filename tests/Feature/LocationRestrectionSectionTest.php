<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\ServiceLocationRestriction;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class LocationRestrectionSectionTest extends TestCase
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
         $response = $this->get('dashboard/service-location-restrictions');
 
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
         $response = $this->get('dashboard/service-location-restrictions/create');
 
         #return 200
         $response->assertStatus(200);
     }

     public function testGoToEdit()
     {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $serviceRestriction = ServiceLocationRestriction::factory()->create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
         #Access the user dashboard
         $response = $this->get('dashboard/service-location-restrictions/edit/'.$serviceRestriction->id);
 
         #return 200
         $response->assertStatus(200);
     }

     public function testUpdateLocationRestriction()
     {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $serviceRestriction = ServiceLocationRestriction::factory()->create();
         $faker = Faker::create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
         $formData = [
            'address' => $faker->address,
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
            'status' => $faker->randomElement([ServiceLocationRestriction::ACTIVE_STATUS,ServiceLocationRestriction::INACTIVE_STATUS]),
            'service' => $faker->randomElement([ServiceLocationRestriction::SERVICE_POINT_TO_POINT,ServiceLocationRestriction::SERVICE_HOURLY,ServiceLocationRestriction::SERVICE_BOTH]),
            'service_limitation' =>$faker->randomElement([ServiceLocationRestriction::SERVICE_LIMITATION_PICKUP,ServiceLocationRestriction::SERVICE_LIMITATION_DROP_OFF]),
            'city_id' => $faker->randomNumber(2),
            'radius' => $faker->randomFloat(2, 1, 100)
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/service-location-restrictions/update/'.$serviceRestriction->id,$formData);
 
         #return 200
         $response->assertStatus(302);
     }

     public function testStoreLocationRestriction()
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
            'address' => $faker->address,
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
            'status' => $faker->randomElement([ServiceLocationRestriction::ACTIVE_STATUS,ServiceLocationRestriction::INACTIVE_STATUS]),
            'service' => $faker->word,
            'service_limitation' => $faker->sentence,
            'city_id' => $faker->randomNumber(2),
            'radius' => $faker->randomFloat(2, 1, 100)
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/service-location-restrictions/store',$formData);
 
         #return 200
         $response->assertStatus(302);
     }
     
     public function testDeleteLocationRestriction()
     {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $serviceRestriction = ServiceLocationRestriction::factory()->create();
         $faker = Faker::create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
         $formData = [
            'address' => $faker->address,
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
            'status' => $faker->randomElement([ServiceLocationRestriction::ACTIVE_STATUS,ServiceLocationRestriction::INACTIVE_STATUS]),
            'service' => $faker->word,
            'service_limitation' => $faker->sentence,
            'city_id' => $faker->randomNumber(2),
            'radius' => $faker->randomFloat(2, 1, 100)
        ];

        #Access the user dashboard
        $response = $this->delete('/dashboard/service-location-restrictions/delete/'.$serviceRestriction->id,$formData);
 
         #return 200
         $response->assertStatus(204);
     }

     public function testCheckIfPlaceAvailable()
     {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $serviceRestriction = ServiceLocationRestriction::factory()->create();
         $faker = Faker::create();
         $lat = $faker->latitude;
         $long = $faker->longitude;
         $radius = $faker->randomFloat(2, 1, 100);
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
         #Access the user dashboard
         $response = $this->get('dashboard/service-location-restrictions/check_if_place_available/'.$lat.'/'.$long.'/'.$radius.'/'.$serviceRestriction->id);
 
         #return 200
         $response->assertStatus(200);
     }
     
}
