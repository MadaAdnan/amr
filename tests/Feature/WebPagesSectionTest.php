<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Fleet;
use App\Models\FleetCategory;
use App\Models\User;
use App\Models\SliderServices;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;


class WebPagesSectionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGoToHome()
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
        $response = $this->get('dashboard/pages/Home');

        #return 200
        $response->assertStatus(200);

    }

    public function testGoToServices()
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
         $response = $this->get('dashboard/pages/Services');
 
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
         $response = $this->get('dashboard/services');
 
         #return 200
         $response->assertStatus(200);
    }

    public function testGoToCreateServices()
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
         $response = $this->get('dashboard/services');
 
         #return 200
         $response->assertStatus(200);
    }

    public function testGoToFleetCategory()
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
          $response = $this->get('dashboard/pages/Fleet%20Category');
  
          #return 200
          $response->assertStatus(200);
    }

    public function testGoToCreateFleetCategory()
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
 
         $user->assignRole($superAdmin);
 
         #make laravel not handling the exceptions
         $this->withoutExceptionHandling();
 
         #Log in the user
         $this->actingAs($user);
 
        #upload file
        $file = UploadedFile::fake()->create('image.png');


        $formData = [
            'image_alt'=>'Test',
            'image'=>$file,
            'title'=>'Neque possimus aliq',
            'short_title'=>'Quia quibusdam id co',
            'slug'=>'Quia tempor eiusmod',
            'category_description'=>'Esse est quo illum',
            'passengers'=>'59',
            'luggage'=>'59',
            'seo_title'=>'958',
            'seo_description'=>'961',
            'seo_keyphrase'=>'762',
            'content'=>'Cillum dolor culpa q',
            'daily_from'=>'22:14',
            'daily_to'=>'16:09',
            'daily_price'=>'537',
            'periodTwentyfour'=>'15',
            'chargeTwentyfour'=>'15',
            'periodNineteen'=>'15',
            'chargeNineteen'=>'15',
            'periodTwelve'=>'15',
            'chargeTwelve'=>'15',
            'periodSix'=>'15',
            'chargeSix'=>'15',
            'minimum_hour'=>'15',
            'mile_per_hour'=>'15',
            'price_per_hour'=>'15',
            'extra_price_per_mile_hourly'=>'15',
            'initial_fee'=>'15',
            'minimum_mile'=>'15',
            'point_to_point_extra_price_per_mile'=>'18',
        ];

        // Simulate a form request
        $response = $this->post('/dashboard/fleet_category/store', $formData);

        // Assert the response, for example:
        $response->assertStatus(302);
    }

    public function testGoToAboutUs()
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
         $response = $this->get('dashboard/pages/About');
 
         #return 200
         $response->assertStatus(200);
 
    }

    public function testUpdateFleetCategory()
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
            'section_one_title'=>'Test',
            'section_one_description'=>'asasd',
            'section_one_paragraph_one_title'=>'Neque possimus aliq',
            'section_one_paragraph_one_description'=>'Quia quibusdam id co',
            'section_one_paragraph_two_title'=>'Quia tempor eiusmod',
            'section_one_paragraph_two_description'=>'Esse est quo illum',
            'section_two_title'=>'Esse est quo illum',
            'section_two_description'=>'Esse est quo illum',
            'section_two_paragraph_one_title'=>'Esse est quo illum',
            'section_two_paragraph_one_description'=>'Esse est quo illum',
            'section_two_paragraph_two_title'=>'Esse est quo illum',
            'section_two_paragraph_two_description'=>'Esse est quo illum',
            'section_three_title'=>'Esse est quo illum',
            'section_three_description'=>'Esse est quo illum'
        ];

        #Simulate a form request
        $response = $this->post('/dashboard/settings/store_about_page', $formData);

        #Assert the response, for example:
        $response->assertStatus(302);
    }

    public function testGoToOrphanPage()
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
          $response = $this->get('dashboard/pages/Orphan');
  
          #return 200
          $response->assertStatus(200);
    }

    public function testGoToCreateSliderServicesPage()
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
          $response = $this->get('dashboard/slider_services/create');
  
          #return 200
          $response->assertStatus(200);
    }
    
    public function testStoreSliderServicePage()
    {
          #Create a user
          $user = User::factory()->create();
          $superAdmin = Role::where('name','Super-admin')->first();
  
          $user->assignRole($superAdmin);
  
          #make laravel not handling the exceptions
          $this->withoutExceptionHandling();
  
          #Log in the user
          $this->actingAs($user);

           #upload file
        $file = UploadedFile::fake()->create('image.png');


        $formData = [
            'image'=>$file,
            'title'=>'Test',
            'link'=>'Neque possimus aliq',
        ];

        // Simulate a form request
        $response = $this->post('/dashboard/slider_services/store', $formData);

        // Assert the response, for example:
        $response->assertStatus(302);
    }

    public function testGoToEditSliderService()
    {
        #Create a user
        $user = User::factory()->create();
        FleetCategory::factory()->create();
        $sliderService = SliderServices::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

      // Simulate a form request
      $response = $this->get('/dashboard/slider_services/edit/'.$sliderService->id);

      // Assert the response, for example:
      $response->assertStatus(200);
    }

    public function testUpdateSliderService()
    {
       #Create a user
       $user = User::factory()->create();
       $superAdmin = Role::where('name','Super-admin')->first();
       $sliderService = SliderServices::factory()->create();

       $user->assignRole($superAdmin);

       #make laravel not handling the exceptions
       $this->withoutExceptionHandling();

       #Log in the user
       $this->actingAs($user);

        #upload file
     $file = UploadedFile::fake()->create('image.png');


     $formData = [
         'image'=>$file,
         'title'=>'Test',
         'link'=>'Neque possimus aliq',
     ];

     // Simulate a form request
     $response = $this->post('/dashboard/slider_services/update/'.$sliderService->id, $formData);

     // Assert the response, for example:
     $response->assertStatus(302);

    }

    public function testDeleteSliderService()
    {
       #Create a user
       $user = User::factory()->create();
       $superAdmin = Role::where('name','Super-admin')->first();
       $sliderService = SliderServices::factory()->create();

       $user->assignRole($superAdmin);

       #make laravel not handling the exceptions
       $this->withoutExceptionHandling();

       #Log in the user
       $this->actingAs($user);

     // Simulate a form request
     $response = $this->post('/dashboard/slider_services/delete/'.$sliderService->id);

     // Assert the response, for example:
     $response->assertStatus(200);

    }

    public function testGoToFleetIndex()
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
        $response = $this->get('dashboard/pages/Fleet');

        #return 200
        $response->assertStatus(200);
    }

    public function testAddFleetContent()
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
            'fleetSeoHeader'=>'Test',
            'link'=>'Neque possimus aliq',
        ];

        // Simulate a form request
        $response = $this->post('/dashboard/pages/createFleetContent', $formData);

        // Assert the response, for example:
        $response->assertStatus(200);
    }
    
    public function testGoToCreateFleet()
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
        $response = $this->get('dashboard/fleet/create');

        #return 200
        $response->assertStatus(200);
    }

    public function testStoreFleet()
    {
          #Create a user
          $user = User::factory()->create();
          $superAdmin = Role::where('name','Super-admin')->first();
  
          $user->assignRole($superAdmin);
  
          #make laravel not handling the exceptions
          $this->withoutExceptionHandling();
  
          #Log in the user
          $this->actingAs($user);

           #upload file
        $file = UploadedFile::fake()->create('image.png');


        $formData = [
            'image'=>$file,
            'image_alt'=>'Test',
            'title'=>'Neque possimus aliq',
            'slug'=>'Neque possimus aliq',
            'content'=>'Neque possimus aliq',
            'categories'=>'1',
            'license'=>'12312',
            'seo_title'=>'asdasd',
            'seo_description'=>'asdasd',
            'seo_keyphrase'=>'asdasd',
        ];

        // Simulate a form request
        $response = $this->post('/dashboard/fleet/store', $formData);

        // Assert the response, for example:
        $response->assertStatus(302);
    }

    public function testGoToEditFleet()
    {
        #Create a user
        $user = User::factory()->create();
        $fleet = Fleet::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

      // Simulate a form request
      $response = $this->get('/dashboard/fleet/edit/'.$fleet->id);

      // Assert the response, for example:
      $response->assertStatus(200);
    }

    public function testUpdateFleet()
    {
       #Create a user
       $user = User::factory()->create();
       $fleet = Fleet::factory()->create();
       $superAdmin = Role::where('name','Super-admin')->first();

       $file = UploadedFile::fake()->create('image.png');

       $formData = [
            'image'=>$file,
            'image_alt'=>'Test',
            'title'=>'Neque possimus aliq',
            'slug'=>'Neque possimus aliq',
            'content'=>'Neque possimus aliq',
            'categories'=>'1',
            'license'=>'12312',
            'seo_title'=>'asdasd',
            'seo_description'=>'asdasd',
            'seo_keyphrase'=>'asdasd',
        ];

       $user->assignRole($superAdmin);

       #make laravel not handling the exceptions
       $this->withoutExceptionHandling();

       #Log in the user
       $this->actingAs($user);

       #Simulate a form request
       $response = $this->post('/dashboard/fleet/update/'.$fleet->id, $formData);

     // Assert the response, for example:
     $response->assertStatus(302); 
    }

    public function testDeleteFleet()
    {
       #Create a user
       $user = User::factory()->create();
       $superAdmin = Role::where('name','Super-admin')->first();
       $sliderService = Fleet::factory()->create();

       $user->assignRole($superAdmin);

       #make laravel not handling the exceptions
       $this->withoutExceptionHandling();

       #Log in the user
       $this->actingAs($user);

     // Simulate a form request
     $response = $this->delete('/dashboard/fleet/delete/'.$sliderService->id);

     // Assert the response, for example:
     $response->assertStatus(204);


    }

    public function testGoToFaq()
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
        $response = $this->get('dashboard/pages/Faq');
        
        #return 200
        $response->assertStatus(200);

        #Access the user dashboard
        $response = $this->get('dashboard/pages/Faq?faqType=Professional%20Chauffeur');

        #return 200
        $response->assertStatus(200);

        #Access the user dashboard
        $response = $this->get('dashboard/pages/Faq??faqType=Cancellations');

        #return 200
        $response->assertStatus(200);
    }

    public function testGoToCreatePageFaq()
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
        $response = $this->get('dashboard/faq/create');
        
        #return 200
        $response->assertStatus(200);
    }

    public function testStoreFaq()
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
            'question'=>'Test',
            'answer'=>'Neque possimus aliq',
            'sort'=>'1',
            'type'=>'Professional Chauffeur'
        ];

        // Simulate a form request
        $response = $this->post('/dashboard/faq/store', $formData);
        
        #return 200
        $response->assertStatus(302);
    }

    public function testGoToEditFaq()
    {
        #Create a user
        $user = User::factory()->create();
        $faq = Faq::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

      // Simulate a form request
      $response = $this->get('/dashboard/fleet/edit/'.$faq->id);

      // Assert the response, for example:
      $response->assertStatus(302);

    }

    public function testUpdateFaq()
    {
        #Create a user
        $user = User::factory()->create();
        $faq = Faq::factory()->create();
        $superAdmin = Role::where('name','Super-admin')->first();

        $user->assignRole($superAdmin);

        #make laravel not handling the exceptions
        $this->withoutExceptionHandling();

        #Log in the user
        $this->actingAs($user);

     
        $formData = [
            'question'=>'Test',
            'answer'=>'Neque possimus aliq',
            'sort'=>'1',
            'type'=>'Cancellations'
        ];

        // Simulate a form request
        $response = $this->delete('/dashboard/faq/update'.$faq->id, $formData);
        
        #return
        $response->assertStatus(302);

      // Assert the response, for example:
      $response->assertStatus(302);

    }

    public function testDeleteFaq()
    {
       #Create a user
       $user = User::factory()->create();
       $superAdmin = Role::where('name','Super-admin')->first();
       $sliderService = Fleet::factory()->create();

       $user->assignRole($superAdmin);

       #make laravel not handling the exceptions
       $this->withoutExceptionHandling();

       #Log in the user
       $this->actingAs($user);

     // Simulate a form request
     $response = $this->delete('/dashboard/fleet/delete/'.$sliderService->id);

     // Assert the response, for example:
     $response->assertStatus(204);


    }

    public function testGoToTerms()
    {
         #Create a user
       $user = User::factory()->create();
       $superAdmin = Role::where('name','Super-admin')->first();

       $user->assignRole($superAdmin);

       #make laravel not handling the exceptions
       $this->withoutExceptionHandling();

       #Log in the user
       $this->actingAs($user);

     // Simulate a form request
     $response = $this->get('dashboard/pages/Terms');


     // Assert the response, for example:
     $response->assertStatus(200);
    }

    public function testUpdateTermsAndPrivacyPolicy()
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
        'terms'=>'content',
        'policy'=>'policy',
        ];

        // Simulate a form request
        $response = $this->post('/dashboard/settings/store_terms', $formData);

        $response->assertStatus(302);
    }

}
