<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogSectionTest extends TestCase
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
        $response = $this->get('/dashboard/blogs');

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
        $response = $this->get('/dashboard/blogs/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreBlog()
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
            'content' => '',
            'image' => '(binary)',
            'slug' => 'Id et aut cillum del',
            'date' => '1992-03-08T07',
            'categories'=> 1,
            'tags'=> 2,
            'seo_title' => 'Et ipsam rerum est sit distinctio Quasi',
            'seo_description' => 'Qui culpa autem et nihil sed',
            'summary' => 'Et labore adipisicin',
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/blogs/store',$formData);

        #return
        $response->assertStatus(302);
    }

    public function testPreviewBlog()
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
            'content' => '',
            'image' => '(binary)',
            'slug' => 'Id et aut cillum del',
            'date' => '1992-03-08T07',
            'categories'=> 1,
            'tags'=> 2,
            'seo_title' => 'Et ipsam rerum est sit distinctio Quasi',
            'seo_description' => 'Qui culpa autem et nihil sed',
            'summary' => 'Et labore adipisicin',
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/blogs/preview',$formData);

        #return
        $response->assertStatus(200);
    }

    public function testStoreWithPublish()
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
            'content' => '',
            'image' => '(binary)',
            'slug' => 'Id et aut cillum del',
            'date' => '1992-03-08T07',
            'categories'=> 1,
            'tags'=> 2,
            'seo_title' => 'Et ipsam rerum est sit distinctio Quasi',
            'seo_description' => 'Qui culpa autem et nihil sed',
            'summary' => 'Et labore adipisicin',
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/blogs/store_with_publish',$formData);

        #return
        $response->assertStatus(201);
    }

    public function testGoToEditBlog()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $post = Post::factory()->create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         #Access the user dashboard
         $response = $this->get('/dashboard/blogs/edit/'.$post->id,);
 
         #return
         $response->assertStatus(200);
    }

    public function testUpdateBlog()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $post = Post::factory()->create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

         $formData = [
            'title' => 'Consequuntur et ulla',
            'content' => '',
            'image' => '(binary)',
            'slug' => 'Id et aut cillum del',
            'date' => '1992-03-08T07',
            'categories'=> 1,
            'tags'=> 2,
            'seo_title' => 'Et ipsam rerum est sit distinctio Quasi',
            'seo_description' => 'Qui culpa autem et nihil sed',
            'summary' => 'Et labore adipisicin',
        ];

        #Access the user dashboard
        $response = $this->post('/dashboard/blogs/update/'.$post->id,$formData);

        $response->assertStatus(302);
    }

    public function testDeleteBlog()
    {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $post = Post::factory()->create();

         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);

        #Access the user dashboard
        $response = $this->get('/dashboard/blogs/delete/'.$post->id);

        $response->assertStatus(200);

    }



}
