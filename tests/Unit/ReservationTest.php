<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationTest extends TestCase
{

    use DatabaseTransactions;


    public function testCheckoutReservation(): void
    {
        /**
         * Add New Reservation
         * 
         * Doc: test the checkout function
         * 
         */

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #generate token
        $user = $this->createTestUser();
        $token = $this->generateAccessToken($user);

        #reservation data 
        $reservationData = 
        [
            'pick_up_location'=>'Central Park',
            'drop_off_location'=>'Empire State Building',
            'pick_up_date'=>'2023/07/12',
            'pick_up_time'=>'9:08 AM',
            'service_type'=>'1',
            'company_id'=>'',
            'driver_id'=>'',
            'price'=>500,
            'distance'=>50,
            'category_id'=>1,
            'return_date'=>'2023/07/12',
            'return_time'=>'9:08 AM',
            'phone_primary'=>'09005600',
            'latitude'=>40.7826,
            'longitude'=>73.9656,
            'dropoff_latitude'=>40.7484,
            'dropoff_longitude'=>73.9857,
            'is_round_trip'=>0,
            'card_id'=>'pm_1OMoCKBO93wCegpN4hjsVT6K',
            'customer_id'=>'cus_PBAWRovNymb2xc',
            'comment'=>'xxxx',
        ];

        $response = $this->sendCheckoutRequest($token, $reservationData);

        $response->assertOk();

        $this->assertTrue(true);
    }

    

    private function createTestUser()
    {
        $user = User::factory()->create();
        $user->update([
            'stripe_id' => 'cus_PB9eeCvmHePRj4', // This is a test customer id from stripe 
        ]);
        return $user;
    }

    private function generateAccessToken(User $user)
    {
        return $user->createToken($user->id . 'testcase')->accessToken;
    }

    private function sendCheckoutRequest($token, $data)
    {
        $header = ['Authorization' => 'Bearer ' . $token];

        return $this->withHeaders($header)
            ->post('api/v2/reservation/checkout', $data);
    }
}
