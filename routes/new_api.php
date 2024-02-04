<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewAPI\NotificationSettingController;
use App\Http\Controllers\NewAPI\ServiceController;
use App\Http\Controllers\NewAPI\ReservationDetailsController as NewReservationDetailsController;
use App\Http\Controllers\NewAPI\Auth\ApiAuthController;
use App\Http\Controllers\NewAPI\UserController;
use App\Http\Controllers\NewAPI\BillingController;
use App\Http\Controllers\NewAPI\StripeController;
use App\Http\Controllers\NewAPI\Auth\ForgetPasswordController;
use App\Http\Controllers\NewAPI\VehiclesController;
use App\Http\Controllers\NewAPI\PostController;

#authorization apis 
Route::controller(ApiAuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/social_login', 'social_login');
    Route::post('/register', 'register');
    Route::post('/requestVerifyPhone', 'RequestVerifyPhone');
});

#forget password apis
Route::controller(ForgetPasswordController::class)->group(function () {
    Route::post('/forget-password', 'index');
    Route::post('/verifyCode', 'verifyCode');
    Route::post('/changePassword', 'changePassword');
});


Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/verifyPhone', [ApiAuthController::class,'verifyPhone'])->name('api.verify_phone');


    #user profile actions    
    Route::prefix("user")->group(function () {

        #security actions
        Route::controller(ApiAuthController::class)->group(function () {
            Route::post('/deactivatedUser', 'deactivatedUser');
            Route::post('/recoveryUser', 'recoveryUser');
            Route::get('send_verification_email','send_verification_email');
            Route::get('check_if_user_verify','check_if_user_verify');
            Route::post('/logout', 'logout');
            Route::get('/details', 'details');//for confirming user token
        });


        #update profile actions
        Route::controller(UserController::class)->group(function () {
            Route::post('/update', 'update');
            Route::post('/upload_image', 'upload_image');
            Route::post('/updatePassword', 'updatePassword');
            Route::post('/set-pin', 'setPinCode');
            Route::post('/check-pin', 'checkPinCode');
            Route::get('/notifications', 'notifications');
        });
     
    });
       
    #get all vehicles
    Route::get('/vehicles', [VehiclesController::class, 'index']);

    //Posts
    Route::prefix("posts")->controller(PostController::class)->group(function () {
        Route::get('/','index');
    });

    Route::prefix("services")->controller(ServiceController::class)->group(function () {
        Route::get('/getServices', 'index');
        
    });

    //bills
    Route::prefix("bills")->controller(BillingController::class)->group(function () {
        Route::get('/history', 'getBillsHistory');
        Route::post('/generateSerial', 'generateSerial');
    });

    //Payment Cards
    Route::prefix("cards")->controller(StripeController::class)->group(function () {
        Route::post('/addCard', 'addCard');
        Route::get('/getCards', 'getCards');
        Route::post('/deleteCard', 'deleteCard');
    });

    //Reservations Details
    Route::prefix("reservation")->controller(NewReservationDetailsController::class)->group(function () {
        Route::get('/', 'getCustomerReservations');
        Route::get('/get-reservation/{id}', 'getInfo');
        Route::post('/report-reservation', 'reportReservation');
        Route::get('cancel-reservation/{id}', 'cancelTrip');
        Route::get('/details', 'index');
        Route::post('trip_info', 'trip_info');
        Route::get('get_coupon/{code}', 'get_coupon');
        Route::post('get_prices', 'getPrices');
        Route::post('checkout', 'checkout');
        Route::post('create_client_secret_from_payment_intent', 'create_client_secret_from_payment_intent');
        Route::get('check_service_availability','check_service_availability');
        Route::get('get_city_timezone/{city_name?}','get_city_timezone');
    });


    Route::prefix("notificationSetting")->controller(NotificationSettingController::class)->group(function () {

        Route::post('addNotificationSetting', 'addNotificationSetting');
        Route::get('notificationStatus', 'notificationStatus');
        Route::get('getNotificationSetting', 'getNotificationSetting');

    });
    


});

