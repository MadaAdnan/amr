<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ChildSeat;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChildSeatsSectionTest extends TestCase
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
        $response = $this->get('dashboard/childSeats');

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
        $response = $this->get('dashboard/childSeats/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testGoToEdit()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $childSeat = ChildSeat::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('dashboard/childSeats/edit/'.$childSeat->id);

        #return 200
        $response->assertStatus(200);
    }

    public function testUpdateChildSeat()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $childSeat = ChildSeat::factory()->create();
        $faker = Faker::create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'title'=>$faker->title,
            'description'=>$faker->sentence(6),
            'price'=>$faker->randomNumber(2),
            'status'=>'Published',

        ];

        #Access the user dashboard
        $response = $this->post('dashboard/childSeats/update/'.$childSeat->id,$formData);

        #return 200
        $response->assertStatus(302);
    }

    public function testStoreChildSeat()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $childSeat = ChildSeat::factory()->create();
        $faker = Faker::create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'title'=>$faker->title,
            'description'=>$faker->sentence(6),
            'price'=>$faker->randomNumber(2),
            'status'=>'Published',

        ];

        #Access the user dashboard
        $response = $this->post('dashboard/childSeats/store',$formData);

        #return 200
        $response->assertStatus(302);
    }

    public function testActiveInActiveSingle()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $childSeat = ChildSeat::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('dashboard/childSeats/activeInactiveSingle/'.$childSeat->id);

        #return 200
        $response->assertStatus(302);
    }
}
