<?php

namespace Tests\Feature;

use App\Models\RedirectMapping;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory as Faker;

class UrlRedirectionSectionTest extends TestCase
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
        $response = $this->get('dashboard/broken-links');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreRedirectMapping()
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

        $formData =  [
            'old_url' => $faker->url,
            'new_url' => $faker->url,
            'is_active' => $faker->boolean,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/broken-links/store',$formData);

        #return
        $response->assertStatus(201);
    }

    public function testUpdateRedirectMapping()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();
        $brokenLink = RedirectMapping::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData =  [
            'old_url' => $faker->url,
            'new_url' => $faker->url,
            'is_active' => $faker->boolean,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/broken-links/update/'.$brokenLink->id,$formData);

        #return
        $response->assertStatus(200);
    }

    public function testDeleteRedirectMapping()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $brokenLink = RedirectMapping::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->delete('/dashboard/broken-links/delete/'.$brokenLink->id);

        #return
        $response->assertStatus(204);
    }

    public function testCheckUrlRedirectMapping()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $brokenLink = RedirectMapping::factory()->create();
        $faker = Faker::create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData =  [
            'url' => $faker->url,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/broken-links/checkUrl',$formData);

        #return
        $response->assertStatus(200);
    }
}
