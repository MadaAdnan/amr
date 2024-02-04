<?php

namespace Tests\Feature;

use App\Models\FleetCategory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\ChildSeat;
use App\Models\Reservation;

class ReservationSectionTest extends TestCase
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
        $response = $this->get('dashboard/reservations');

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
        $response = $this->get('dashboard/reservations/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreReservation()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $category = FleetCategory::factory()->create(); 
        $seat = ChildSeat::factory()->create();
        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'service_type' => 1,
            'pick_up_location' => 'New York, NY, USA',
            'drop_off_location' => 'New York, NY, USA',
            'city' => null,
            'company_booking_number' => 'AAA-F',
            'company_id'=> 1,
            'duration'=> 0,
            'start_date' => '2024-01-25',
            'start_time' => '04:11',
            'airline_id' => '1',
            'flight_number' => 8,
            'fleet_category' => $category->id, // create factory
            'chaffeur' => $user->id, // create factory
            'miles'=>40,
            'pickup_sign'=>'Test Content',
            'coupon_code'=>'',
            'email_for_confirmation'=>'',
            'comment'=>'hello this is test',
            'first_name'=>'Qusai',
            'last_name'=>'Zn',
            'email'=>'tegokapi@mailinator.com',
            'phone'=>'+1 (658) 686-5542',
            'price'=>46998.00,
            'name'=>'New York',
            'latitude'=>'40.7127753',
            'longitude'=>'-74.0059728',
            'drop_latitude'=>'40.7127753',
            'drop_longitude'=>'-74.0059728',
            'drop_name'=>'New York',
            'country_code'=>1,
            'seats[0][seat]'=>$seat->id,
            'seats[0][amount]'=>1

        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/reservations/store',$formData);

        #return
        $response->assertStatus(201);
    }

    public function testGoToEditReservation()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $post = Reservation::factory()->create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         #Access the user dashboard
         $response = $this->get('/dashboard/reservations/edit/'.$post->id,);
 
         #return
         $response->assertStatus(200);
    }

    public function testUpdateReservation()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $category = FleetCategory::factory()->create(); 
         $seat = ChildSeat::factory()->create();
         $post = Reservation::factory()->create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         $formData = [
            'service_type' => 1,
            'pick_up_location' => 'New York, NY, USA',
            'drop_off_location' => 'New York, NY, USA',
            'city' => null,
            'company_booking_number' => 'AAA-F',
            'company_id'=> 1,
            'duration'=> 0,
            'start_date' => '2024-01-25',
            'start_time' => '04:11',
            'airline_id' => '1',
            'flight_number' => 8,
            'fleet_category' => $category->id, // create factory
            'chaffeur' => $user->id, // create factory
            'miles'=>40,
            'pickup_sign'=>'Test Content',
            'coupon_code'=>'',
            'email_for_confirmation'=>'',
            'comment'=>'hello this is test',
            'first_name'=>'Qusai',
            'last_name'=>'Zn',
            'email'=>'tegokapi@mailinator.com',
            'phone'=>'+1 (658) 686-5542',
            'price'=>46998.00,
            'name'=>'New York',
            'latitude'=>'40.7127753',
            'longitude'=>'-74.0059728',
            'drop_latitude'=>'40.7127753',
            'drop_longitude'=>'-74.0059728',
            'drop_name'=>'New York',
            'country_code'=>1,
            'seats[0][seat]'=>$seat->id,
            'seats[0][amount]'=>1

        ];

        #send request
        $response = $this->post('/dashboard/reservations/update/'.$post->id,$formData);

        $response->assertStatus(201);
    }

    public function testGoToShowReservation()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $post = Reservation::factory()->create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         #Access the user dashboard
         $response = $this->get('/dashboard/reservations/show/'.$post->id,);
 
         #return
         $response->assertStatus(200);
    }

}
