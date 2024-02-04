<?php

use App\Http\Controllers\FrontEnd\HomeController;
use App\Http\Controllers\FrontEnd\ReservationController;
use App\Http\Controllers\FrontEnd\ServiceController;
use App\Http\Controllers\FrontEnd\BlogController;
use App\Http\Controllers\FrontEnd\FleetController;
use App\Http\Controllers\FrontEnd\AboutUsController;
use App\Http\Controllers\FrontEnd\AffiliateController;
use App\Http\Controllers\FrontEnd\ChauffeurController;
use App\Http\Controllers\FrontEnd\PolicyController;
use App\Http\Controllers\FrontEnd\FaqController;
use App\Http\Controllers\FrontEnd\Auth\AuthController;
use App\Http\Controllers\FrontEnd\ContactUsController;
use App\Http\Controllers\FrontEnd\Profile\User\HomeController as UserProfileHomeController;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect()->route('frontEnd.user.profile.home');
})->middleware(['auth', 'signed'])->name('verification.verify');





Route::name('frontEnd.')->group(function(){

    #Home page
    Route::get('/',[HomeController::class,'index'])->name('index');

    #get blogs according to categories
    Route::get('category/{slug}',[BlogController::class,'categories'])->name('categories.blogs');

    #services
    Route::prefix('services')->name('services.')->controller(ServiceController::class)->group(function(){
        Route::get('/{slug}','details')->name('details');
    });

    #blogs
    Route::prefix('articles')->name('blogs.')->controller(BlogController::class)->group(function(){
        Route::get('/','index')->name('index');
        Route::get('{slug}','details')->name('details');
    });

    #fleets
    Route::prefix('fleet')->name('fleets.')->controller(FleetController::class)->group(function(){
        Route::get('/','index')->name('index');
        Route::get('/{slug}','details')->name('details');
    });

    #AboutUs
    Route::prefix('about-us')->name('aboutUs.')->controller(AboutUsController::class)->group(function(){
        Route::get('/','index')->name('index');
    });

    #Affiliate
    Route::prefix('affiliate')->name('affiliate.')->controller(AffiliateController::class)->group(function(){
        Route::get('/','index')->name('index');
        Route::get('/create','create')->name('create');
        Route::post('store','store')->name('store');
        Route::get('thank-you','thank_you')->name('thank_you')->middleware('CheckForSubmissionThankYouPage');
    });

    #Chauffeur
    Route::prefix('chauffeur')->name('chauffeur.')->controller(ChauffeurController::class)->group(function(){
        Route::get('/','index')->name('index');
        Route::get('/create','create')->name('create');
        Route::post('store','store')->name('store');
        Route::get('thank-you','thank_you')->name('thank_you')->middleware('CheckForSubmissionThankYouPage');
    });

    #Policy
    Route::name('policy.')->controller(PolicyController::class)->group(function(){
        Route::get('terms-and-conditions','terms_condition')->name('terms_condition');
        Route::get('privacy-policy','privacy_policy')->name('privacy_policy');
    });

    #Faq's
    Route::prefix('faq')->name('faq.')->controller(FaqController::class)->group(function(){
        Route::get('/','index')->name('index');
    });

    #ContactUs
    Route::prefix('contact-us')->name('contactUs.')->controller(ContactUsController::class)->group(function(){
        Route::get('/','index')->name('index');
        Route::post('store','store')->name('store');
        Route::get('thank-you','thank_you')->name('thank_you')->middleware('CheckForSubmissionThankYouPage');
    });

    #reservation system
    Route::name('reservations.')->middleware('reservation.steps')
    ->controller(ReservationController::class)
    ->prefix('reservations')
    ->group(function () {
        Route::get('/', 'choose_location')->name('choose_location');
        Route::get('vehicles', 'select_fleet')->name('select_fleet');
        Route::get('checkout', 'checkout')->name('checkout')->middleware('CheckVerifyPhoneNumber');
        Route::post('login', 'login')->name('login');
        Route::get('places_details/{place_id}', 'places_details')->name('place_details');
        Route::post('checkout_submit/{reservation_id}', 'checkout_submit')->name('checkout_submit');
    
        Route::get('thank-you', 'thank_you')->name('thank_you');
        Route::post('store', 'store')->name('store');
        Route::post('update_fleet/{id}', 'update_fleet')->name('update_fleet');
        Route::get('check_info', 'check_info')->name('check_info');
        Route::get('get_coupon_code/{code}', 'get_coupon_code')->name('get_coupon_code');
    });

    #user
    Route::prefix('user')->group(function()
    {
        #login
        Route::name('auth.')->controller(AuthController::class)->group(function(){
            Route::get('login','login')->name('login')->middleware('ensureGuestMiddleware');
            Route::post('login_submit','login_submit')->name('login_submit')->middleware('ensureGuestMiddleware');
            Route::get('register','register')->name('register')->middleware('ensureGuestMiddleware');
            Route::post('register_submit','register_submit')->name('register_submit')->middleware('ensureGuestMiddleware');
            Route::get('logout','logout')->name('logout');
            Route::get('forget-password','forget_password')->name('forget_password');
            Route::post('forget-password-submit','forget_password_submit')->name('forget_password_submit');
            Route::post('reset-password','reset_password')->name('reset_password');
            Route::post('check-if-email-exist/{update_email?}','check_if_email_exist')->name('check_if_email_exist');

            //otp steps
            Route::post('check_unique_phone_number','check_unique_phone_number')->name('check_unique_phone_number');
            Route::middleware(['auth'])->group(function(){
                Route::get('otp','otp')->name('otp');
                Route::get('change-phone-number','change_phone_number')->name('change_phone_number');
                Route::post('update-phone-number','update_phone_number')->name('update_phone_number');
                Route::post('verify-code','verify_code')->name('verify_code');
                Route::post('resend-otp','resendOtp')->name('resendOtp');
            });
        });

        #profile
        Route::middleware('auth')->prefix('profile')->name('user.profile.')->group(function(){

            #index of the user profile
            Route::controller(UserProfileHomeController::class)->group(function(){

                #go to the main page
                Route::get('/','index')->name('home');
                
                Route::post('update-personal-information','update_personal_information')->name('update_personal_information');
                Route::post('update-email-address','update_email_address')->name('update_email_address');

            });
        });
        
    });

});









