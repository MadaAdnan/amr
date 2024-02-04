<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ad;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;

class AdsSectionTest extends TestCase
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
        $response = $this->get('dashboard/ads/index');

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
        $response = $this->get('dashboard/ads/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreAds()
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

        $image = UploadedFile::fake()->create('image.png');

        $formData = [
            'title' => $faker->sentence,
            'image'=>$image,
            'description' => $faker->paragraph,
            'start_date' => $faker->date,
            'end_date' => $faker->date,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/ads/store',$formData);

        #return
        $response->assertStatus(302);
    }

    public function testGoToEdit()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $ad = Ad::factory()->create();
        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/ads/edit/'.$ad->id);

        #return 200
        $response->assertStatus(200);
    }

    public function testUpdateAds()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();
        $ad = Ad::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $image = UploadedFile::fake()->create('image.png');

        $formData = [
            'title' => $faker->sentence,
            'image'=>$image,
            'description' => $faker->paragraph,
            'start_date' => $faker->date,
            'end_date' => $faker->date,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/ads/update/'.$ad->id,$formData);

        #return
        $response->assertStatus(302);
    }

}
