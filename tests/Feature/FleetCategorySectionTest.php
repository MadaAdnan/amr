<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FleetCategory;
use App\Models\Fleet;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;


class FleetCategorySectionTest extends TestCase
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
        $response = $this->get('dashboard/fleet_category/index');

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
        $response = $this->get('dashboard/fleet_category/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreFleetCategory()
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
            'title' => $faker->word,
            'slug' => $faker->unique()->slug,
            'image_alt' => $faker->word,
            'category_description' => $faker->sentence,
            'passengers' => $faker->randomNumber(2),
            'luggage' => $faker->randomNumber(2)
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/fleet_category/store',$formData);

        #return
        $response->assertStatus(302);
    }

    public function testGoToEdit()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $fleetCategory = FleetCategory::factory()->create();
        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('dashboard/fleet_category/edit/'.$fleetCategory->id);

        #return 200
        $response->assertStatus(200);
    }

    public function testUpdateFleetCategory()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();
        $fleetCategory = FleetCategory::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('image.png', 100);

        $formData = [
            'title' => $faker->word,
            'slug' => $faker->unique()->slug,
            'image_alt' => $faker->word,
            'image'=>$file,
            'category_description' => $faker->sentence,
            'passengers' => $faker->randomNumber(2),
            'luggage' => $faker->randomNumber(2)
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/fleet_category/update/'.$fleetCategory->id,$formData);

        #return
        $response->assertStatus(302);
    }

    public function testCheckSlug()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $faker = Faker::create();
        $slug = $faker->slug();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('dashboard/fleet_category/check_slug/'.$slug);

        #return 200
        $response->assertStatus(200);
    }

    public function testDeleteFleetCategory()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $fleetCategory = FleetCategory::factory()->create();
        $fleetCategoryAttached = Fleet::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'fleet_category' => $fleetCategoryAttached->id,
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/fleet_category/delete/'.$fleetCategory->id,$formData);

        #return 200
        $response->assertStatus(200);
    }

}
