<?php

namespace Tests\Feature\Api;

use App\Models\ForgetPassword;
use Tests\TestCase;
use App\Models\User;
use App\Models\OtpPhone;
use App\Models\OtpDevice;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthApisTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseTransactions;

    public function testLogin()
    {
        #create customer
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);

        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);

       #send data
       $formData =  [
            'email' => $user->email,
            'password' => '123456',
            'fcm' => 'secret'
        ];


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
                "token"
            ],
            'success',
            'error'
        ];
        
        
        #send request
        $response = $this->json('POST', '/api/v2/login', $formData)
        ->assertJsonStructure($responseFormat);

        #expected status
        $response->assertStatus(200);
    }

    public function testRegister()
    {
        #data
       $faker = Faker::create();
       #send data
       $formData =  [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'type' => $faker->randomElement(['social', 'normal']),
            'country_code' => $faker->randomNumber(2),
            'fcm' => $faker->text(255),
            'phone' => $faker->unique()->phoneNumber,
            'email' => $faker->unique()->safeEmail,
            'password' => $faker->password(8),
            'user_id' => $faker->uuid,
        ];


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
                "token"
            ],
            'success',
            'error'
        ];
        
        
        #send request
        $response = $this->json('POST', '/api/v2/register', $formData)
        ->assertJsonStructure($responseFormat);

        #expected status
        $response->assertStatus(200);
    }

    public function testLogout()
    {
        #create customer
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);

        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);


        #response format
        $responseFormat = [
            'results' ,
            'success',
            'error'
        ];
        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;

        $tokenHeader = ['Authorization' => 'Bearer ' . $token];

        
        
        #send request
        $response = $this->withHeaders($tokenHeader)
        ->postJson('/api/v2/user/logout');

        #check response
        $response
        ->assertJsonStructure($responseFormat)
        ->assertStatus(200);    
    }

    public function testRequestVerifyPhone()
    {
       #send data
       $formData =  [
            'device_id' => 'Test',
            'phone' =>'0775729329',
            'country_code' => '962'
        ];

        #response format
        $responseFormat = [
            'results'=>[
                'success',
                'otp_code'
            ],
            'success',
            'error'
        ];

        #send request
        // $response = $this->postJson('/api/v2/requestVerifyPhone',$formData);

        #check response
        // $response
        // ->assertJsonStructure($responseFormat)
        // ->assertStatus(200);    
    }

    public function testForgetPassword()
    {
       #data
       $user = User::factory()->create();

       #send data
       $formData =  [
        'email' => $user->email,
       ];

        #response format
        $responseFormat = [
            'results',
            'success',
            'error'
        ];

        #send request
        //$response = $this->postJson('/api/v2/forget-password',$formData);

        #check response
        // $response
        // ->assertJsonStructure($responseFormat)
        // ->assertStatus(200);    
    }

    public function testVerifyCode()
    {
       #data
       $forgetPassword = ForgetPassword::factory()->create();

       #send data
       $formData =  [
        'user_id' => $forgetPassword->user_id,
        'code' => $forgetPassword->code,
       ];

        #response format
        $responseFormat = [
            'success',
            'results',
            'error'
        ];

        #send request
        $response = $this->postJson('/api/v2/verifyCode',$formData);

        #check response
        $response
        ->assertJsonStructure($responseFormat)
        ->assertStatus(200);  
    }

    public function testChangePassword()
    {
       #data
       $forgetPassword = ForgetPassword::factory()->create();

       #send data
       $formData =  [
        'user_id' => $forgetPassword->user_id,
        'new_password' => '12345678',
       ];

        #send request
        $response = $this->postJson('/api/v2/changePassword',$formData);

        #check response
        $response
        ->assertStatus(200);
    }

    public function testVerifyPhone()
    {
        #create customer
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);
        $otpPhoneFactory = OtpPhone::factory()->create();
        $otpDevice = OtpDevice::factory()->create();
        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);


        #send data
        $sentData = [
            'country_code'=>1,
            'device_id'=>$otpDevice->id,
            'otp_code'=>$otpPhoneFactory->otp_code,
            'number'=>$otpPhoneFactory->phone
        ];

       
        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;

        $tokenHeader = ['Authorization' => 'Bearer ' . $token];
        
        
        #send request
        $response = $this->withHeaders($tokenHeader)
        ->postJson('/api/v2/verifyPhone',$sentData);

        #check response
        $response
        ->assertStatus(200);    

    }

    public function testDeactivateUser()
    {
        #create customer
        $user = User::factory()->create();
        $customer = Role::where('name','Customer')->first();
        $user->assignRole($customer);

        #update the password
        $user->update([
            'password'=>bcrypt(123456)
        ]);

       
        $token = $user->createToken($user->id . 'Test' . date('Y-m-d H:i:s'))->accessToken;

        $tokenHeader = ['Authorization' => 'Bearer ' . $token];
        
        
        #send request
        $response = $this->withHeaders($tokenHeader)
        ->postJson('api/v2/user/deactivatedUser');

        #check response
        $response
        ->assertStatus(200);    

    }

}
