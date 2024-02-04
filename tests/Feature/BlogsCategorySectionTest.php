<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogsCategorySectionTest extends TestCase
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
        $response = $this->get('/dashboard/categories');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreBlogCategory()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'title' => 'Consequuntur et ulla',
            'seo_title' => 'test',
            'seo_description' => 'test',
            'seo_keyphrase' => 'test',
            'slug'=> 'test',
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/categories/store',$formData);

        #return
        $response->assertStatus(201);
    }

    public function testUpdateBlogCategory()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $category = Category::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        $formData = [
            'title' => 'Consequuntur et ulla',
            'seo_title' => 'test',
            'seo_description' => 'test',
            'seo_keyphrase' => 'test',
            'slug'=> 'test',
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/categories/update/'.$category->id,$formData);

        #return
        $response->assertStatus(201);
    }

    public function testDeleteBlogCategory()
    {
        #Create a user
        $user = User::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();
        $category = Category::factory()->create();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

        #Access the user dashboard
        $response = $this->delete('/dashboard/categories/delete/'.$category->id);

        #return
        $response->assertStatus(204);
    }

}
