<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\ChildSeat;
use App\Models\Reservation;

class ReservationApisTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testGetReservationDetails()
    {
        #Create a user
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);

        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

         #response format
         $responseFormat = [
            'results'=>[
                'childSeats',
                'duration',
                'airlines'
            ],
            'success',
            'error'
        ];


        #Log in the user
        $this->actingAs($user);

        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;
        $tokenHeader = ['Authorization' => 'Bearer ' . $token];


        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->getJson('api/v2/reservation/details');


        $response->assertStatus(200);
        $response->assertJsonStructure($responseFormat);
    }

    public function testGetReservationReservationInfo()
    {
        #Create a user
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);
        $reservation = Reservation::factory()->create();
        $reservation->update([
            'user_id'=>$user->id
        ]);

        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();



        #Log in the user
        $this->actingAs($user);

        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;
        $tokenHeader = ['Authorization' => 'Bearer ' . $token];


        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->getJson('api/v2/get-reservation/'.$reservation->id);


        $response->assertStatus(200);
    }

    public function testGetPrice()
    {
        #Create a user
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);

        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

         #response format
         $responseFormat = [
            'results',
            'success',
            'error'
        ];


        #Log in the user
        $this->actingAs($user);

        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;
        $tokenHeader = ['Authorization' => 'Bearer ' . $token];

        $faker = Faker::create();
        $createChildSeat = ChildSeat::factory()->create();

        $sentData = [
            'city_name' =>$faker->city,
            'service_id' =>1,
            'distance' =>$faker->numberBetween(1, 100), // optional field
            'duration' =>0, 
            'is_round_trip' =>$faker->numberBetween(0, 1),
            'pick_up_location' =>$faker->address,
            'drop_off_location' =>$faker->address, // optional field
            'pick_up_date' =>$faker->date,
            'pick_up_time' =>$faker->time,
            'child_seats' =>[$createChildSeat->id], // optional array
            'lat' =>$faker->latitude,
            'long' =>$faker->longitude,
        ];

        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->postJson('api/v2/reservation/get_prices',$sentData);
        
        \Log::info('API Response: ' . $response->getContent());


        $response->assertStatus(200);
        $response->assertJsonStructure($responseFormat);
    }

}
