<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;

class UserApisTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    public function testUpdateApi()
    {
        #Create a user
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);
        $faker = Faker::create();

        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

         #response format
         $responseFormat = [
            'results' => [
                "user_id",
                "first_name",
                "last_name",
                "email",
                "phone",
                "country_code",
                "image",
                "role_id",
                "deactivated",
                "is_verified",
                "customer_id",
                "is_phone_verify",
            ],
            'success',
            'error'
        ];


        #Log in the user
        $this->actingAs($user);

        $sentData = [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->unique()->phoneNumber,
            'country_code' => $faker->randomNumber(2),
        ];

        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;
        $tokenHeader = ['Authorization' => 'Bearer ' . $token];


        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->postJson('api/v2/user/update',$sentData);

        #return 200
        $response->assertJsonStructure($responseFormat);
        $response->assertStatus(200);
    }

    public function testUploadImageApi()
    {
        #Create a user
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);

        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);

        $image = UploadedFile::fake()->create('image.png');

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

        $sentData = [
            'image' => $image,
        ];

        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;
        $tokenHeader = ['Authorization' => 'Bearer ' . $token];


        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->postJson('api/v2/user/upload_image',$sentData);

        #return 200
        $response->assertJsonStructure($responseFormat);
        $response->assertStatus(200);
    }

    public function testUpdatePasswordApi()
    {
        #Create a user
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);
        $faker = Faker::create();

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
        $password = $faker->password(8);

        $sentData = [
            'current_password' => 123456,
            'new_password' => $password,
            'confirm_password' => $password,
        ];

        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;
        $tokenHeader = ['Authorization' => 'Bearer ' . $token];


        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->postJson('api/v2/user/updatePassword',$sentData);

        #return 200
        $response->assertJsonStructure($responseFormat);
        $response->assertStatus(200);
    }

    public function testSetPinApi()
    {
        #Create a user
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);
        $faker = Faker::create();

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
        $password = $faker->randomNumber(2);
        
        $sentData = [
            'local_pin' => $password,
        ];

        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;
        $tokenHeader = ['Authorization' => 'Bearer ' . $token];


        #Access the user dashboard
        $response = $this->withHeaders($tokenHeader)
        ->postJson('api/v2/user/set-pin',$sentData);


        #return 200
        $response->assertJsonStructure($responseFormat);
        $response->assertStatus(200);
    }

    public function testNotificationApi()
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
        ->getJson('api/v2/user/notifications');


        #return 200
        $response->assertJsonStructure($responseFormat);
        $response->assertStatus(200);
    }

    public function testCheckPinApi()
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
        ->getJson('api/v2/user/notifications');


        #return 200
        $response->assertJsonStructure($responseFormat);
        $response->assertStatus(200);
    }
}
