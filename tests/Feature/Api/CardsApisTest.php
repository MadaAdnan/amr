<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Traits\UtilitiesTrait;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class CardsApisTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     use UtilitiesTrait;

    public function testAddCard()
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

        $faker = Faker::create();

        $sentData = [
            'card_number' => '4111 1111 1111 1111',
            'exp_month' => $faker->numberBetween(1, 12),
            'exp_year' => $faker->numberBetween(now()->year, now()->year + 10),
            'cvc' => $faker->numberBetween(100, 999),
            'name' => $faker->name,
            'address' => $faker->streetAddress,
            'line1' => $faker->streetAddress,
            'city' => $faker->city,
            'state' => $faker->state,
            'postel_code' => $faker->postcode,
            'country' => 'US',
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
            'customer_id' => $this->generateCustomerId($user->email,$user->name),
        ];

        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;
        $tokenHeader = ['Authorization' => 'Bearer ' . $token];


        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->postJson('api/v2/cards/addCard',$sentData);

        \Log::info('API Response: ' . $response->getContent());

        $response->assertJsonStructure($responseFormat);

        $response->assertStatus(200);
    }

    public function testGetCard()
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


        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->getJson('api/v2/cards/getCards');


        $response->assertStatus(200);
        $response->assertJsonStructure($responseFormat);

    }
    
}
