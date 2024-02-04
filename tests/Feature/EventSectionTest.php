<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\City;
use App\Models\Event;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventSectionTest extends TestCase
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
        $response = $this->get('dashboard/events');

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
        $response = $this->get('dashboard/events/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreEvent()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();
        $city = City::factory()->create();
        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'name' => $faker->word,
            'start_date' => $faker->date,
            'city_id' => $city->id,
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
            'radius' => $faker->randomFloat(2, 1, 100),
            'radius_area' => json_encode([]),
            'price' => $faker->randomFloat(2, 10, 100),
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/events/store',$formData);

        #return
        $response->assertStatus(201);
    }

    public function testGoToEdit()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $event = Event::factory()->create();
        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('dashboard/fleet_category/edit/'.$event->id);

        #return 200
        $response->assertStatus(302);
    }

    public function testUpdateEvent()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();
        $city = City::factory()->create();
        $event = Event::factory()->create();
        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'name' => $faker->word,
            'start_date' => $faker->date,
            'city_id' => $city->id,
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
            'radius' => $faker->randomFloat(2, 1, 100),
            'radius_area' => json_encode([]),
            'price' => $faker->randomFloat(2, 10, 100),
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/events/update/'.$event->id,$formData);

        #return
        $response->assertStatus(201);
    }

    public function testDelete()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $event = Event::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->delete('dashboard/events/delete/'.$event->id);

        #return 200
        $response->assertStatus(201);
    }


}
