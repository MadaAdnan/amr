<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Coupon;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponSectionTest extends TestCase
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
         $response = $this->get('dashboard/coupons');
 
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
         $response = $this->get('dashboard/coupons/create');
 
         #return 200
         $response->assertStatus(200);
     }

     public function testGoToEdit()
     {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $coupon = Coupon::factory()->create();
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
         #Access the user dashboard
         $response = $this->get('dashboard/coupons/edit/'.$coupon->id);
 
         #return 200
         $response->assertStatus(200);
     }

     public function testUpdateCoupon()
     {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $faker = Faker::create();
         $coupon = Coupon::factory()->create();
 
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
 
         $formData = [
            'coupon_name' => $faker->word,
            'coupon_code' => Str::upper($faker->unique()->randomLetter . $faker->unique()->randomLetter . $faker->unique()->randomLetter),
            'usage_limit' => $faker->randomNumber(2),
            'percentage_discount' => $faker->randomFloat(2, 1, 100),
            'active_from' => $faker->dateTimeBetween('now', '+30 days'),
            'active_to' => $faker->dateTimeBetween('+31 days', '+60 days'),
            'discount_type' => $faker->randomElement(['percentage', 'fixed']),
         ];
 
         #Access the user dashboard
         $response = $this->post('/dashboard/coupons/update/'.$coupon->id,$formData);
 
         #return
         $response->assertStatus(302);
     }

     public function testStoreCoupon()
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
            'coupon_name' => $faker->word,
            'coupon_code' => Str::upper($faker->unique()->randomLetter . $faker->unique()->randomLetter . $faker->unique()->randomLetter),
            'usage_limit' => $faker->randomNumber(2),
            'percentage_discount' => $faker->randomFloat(2, 1, 100),
            'active_from' => $faker->dateTimeBetween('now', '+30 days'),
            'active_to' => $faker->dateTimeBetween('+31 days', '+60 days'),
            'discount_type' => $faker->randomElement(['percentage', 'fixed']),
         ];
 
         #Access the user dashboard
         $response = $this->post('/dashboard/coupons/store',$formData);
 
         #return
         $response->assertStatus(302);
     }

     public function testSoftDelete()
     {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $coupon = Coupon::factory()->create();
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
         #Access the user dashboard
         $response = $this->post('dashboard/coupons/softDelete/'.$coupon->id);
 
         #return 200
         $response->assertStatus(302);
     }

     public function testGoToShowSoftDeletedCoupons()
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
         $response = $this->get('dashboard/coupons/showSoftDelete');
 
         #return 200
         $response->assertStatus(200);
     }

     public function testRestoreDeletedCoupons()
     {
         #Create a user
         $user = User::factory()->create();
         $superAdmin = Role::where('name','Super-admin')->first();
         $user->assignRole($superAdmin);
         $coupon = Coupon::factory()->create();

         $coupon->deleted_at = Carbon::now();
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
         #Access the user dashboard
         $response = $this->get('dashboard/coupons/softDeleteRestore/'.$coupon->id);
 
         #return 200
         $response->assertStatus(302);
     }
}
