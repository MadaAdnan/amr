<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class TagSectionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

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
        $response = $this->get('dashboard/tags');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreKeyword()
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

        $formData = [
            'title' => $faker->sentence,
            'slug' => $faker->slug,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/tags/store',$formData);

        #return
        $response->assertStatus(200);
    }
}
