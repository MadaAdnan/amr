<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class KeyWordBankSectionTest extends TestCase
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
        $response = $this->get('dashboard/keywords');

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
            'subject' => $faker->word,
            'strength' => $faker->randomDigitNotNull,
            'monthly_volume' => $faker->randomNumber(),
            'is_keyword' => $faker->boolean,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/keywords/store',$formData);

        #return
        $response->assertStatus(200);
    }

    public function testUpdateKeyword()
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
            'subject' => $faker->word,
            'strength' => $faker->randomDigitNotNull,
            'monthly_volume' => $faker->randomNumber(),
            'is_keyword' => $faker->boolean,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/keywords/store',$formData);

        #return
        $response->assertStatus(200);
    }


}
