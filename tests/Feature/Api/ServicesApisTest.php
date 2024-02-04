<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ServicesApisTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testGetServices()
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
         ->getJson('api/v2/services/getServices');
 
         #return 200
         $response->assertJsonStructure($responseFormat);
         $response->assertStatus(200);
    }
}
