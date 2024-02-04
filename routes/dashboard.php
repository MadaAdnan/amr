<?php

use App\Http\Controllers\Dashboard\SeoCitiesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\BlogController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\ChildSeatController;
use App\Http\Controllers\Dashboard\CommentController;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\DriverController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\AdsController;
use App\Http\Controllers\Dashboard\CitiesController;
use App\Http\Controllers\Dashboard\CountriesController;
use App\Http\Controllers\Dashboard\EventsController;
use App\Http\Controllers\Dashboard\FaqController;
use App\Http\Controllers\Dashboard\FleetCategoryController;
use App\Http\Controllers\Dashboard\FleetController;
use App\Http\Controllers\Dashboard\LocationController;
use App\Http\Controllers\Dashboard\LogsController;
use App\Http\Controllers\Dashboard\RedirectMappingController;
use App\Http\Controllers\Dashboard\ReservationController;
use App\Http\Controllers\Dashboard\ServiceLocationRestrictionController;
use App\Http\Controllers\Dashboard\ServicesController;
use App\Http\Controllers\Dashboard\SliderServicesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\StateController;
use App\Http\Controllers\Dashboard\TagsController;
use App\Http\Controllers\Dashboard\TrashController;
use App\Http\Controllers\Dashboard\HomeController as DashboardHomeController;
use App\Http\Controllers\Dashboard\SeoCountriesController;

/** Auth  */
Route::middleware('checkLogin')->prefix('auth')->controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('dashboard.login');
    Route::post('login_submit', 'login_submit')->name('login_submit');
});

Route::middleware('permission:analytics|show-reservation|pending-web-pages|pending-blogs|web-pages|blogs|blog-categories|keywords-banks|create-web-pages|show-publish-date|show-slug|show-keywords|show-category|show-seo-title|show-description|show-title|submit-web-pages|submit-blogs')
->name('dashboard.')
->prefix('dashboard')->group(function () {

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        /** Home Controller (Main Page) */
        Route::get('/home', [DashboardHomeController::class, 'index'])->name('home')->middleware('checkAdmin');
        Route::post('upload_an_Image_ck_editor', [DashboardHomeController::class, 'upload_an_Image_ck_editor'])->name('upload_an_Image_ck_editor');

        /** Categories */
        Route::middleware('permission:show-category|blog-categories')->name('categories.')->controller(CategoriesController::class)->prefix('categories')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::get('get_categories_select', 'get_categories_select')->name('get_categories_select');
            Route::get('pending', 'pending')->name('pending');
            Route::delete('delete/{id}', 'delete')->name('delete');
        });
        
        /** Tags */
        Route::middleware('permission:show-keywords|keywords-banks')->name('tags.')->controller(TagsController::class)->prefix('tags')->group(function () {
            Route::get('/', 'get_tags')->name('index');
            Route::post('/store', 'tag_store')->name('store');
            Route::post('/update/{id}', 'tag_update')->name('update');
            Route::delete('/delete/{id}', 'tag_delete')->name('delete');
        });

        /** Blogs */
        Route::middleware('permission:submit-blogs|blogs|pending-blogs')->name('blogs.')->controller(BlogController::class)->prefix('blogs')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::get('check_slug/{slug}', 'check_slug')->name('check_slug');
            Route::get('pending', 'pending')->name('pending');
            Route::get('review/{id}', 'review')->name('review');
            Route::post('send_reject_note/{id}/{status?}', 'send_reject_note')->name('send_reject_note');
            Route::post('save_data/{id}', 'save_data')->name('save_data');
            Route::get('get_selected_data_select2/{id}', 'get_selected_data_select2')->name('get_selected_data_select2');
            Route::post('update/{id}/{status?}', 'update')->name('update');
            Route::post('publish/{id}', 'publish')->name('publish');
            Route::post('store_with_publish', 'store_with_publish')->name('store_with_publish');
            Route::get('delete/{id}', 'delete')->name('delete');
            Route::post('release/{id}', 'release')->name('release');
            Route::post('preview/{id?}', 'preview')->name('preview');
            Route::get('search_select_2', 'search_select_2')->name('search_select_2');
        });

        /** Pages */
        Route::middleware('permission:web-pages|submit-web-pages')->name('pages.')->controller(PageController::class)->prefix('pages')->group(function () {
            Route::get('/{type}', 'index')->name('index')->where(['type' => config('general.dashboard_page_types')]);
            Route::get('/create', 'create')->name('create');
            Route::post('send_reject_note/{id}', 'send_reject_note')->name('send_reject_note');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('pending', 'pending')->name('pending');
            Route::get('review/{id}', 'review')->name('review');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('store', 'store')->name('store');
            Route::post('store_with_publish/{id?}', 'store_with_publish')->name('store_with_publish');
            Route::get('check_slug/{slug}', 'check_slug')->name('check_slug');
            Route::delete('delete/{id}', 'delete')->name('delete');
            Route::post('preview/{id?}', 'preview')->name('preview');
            Route::post('release/{id}', 'release')->name('release');
            Route::post('createCountryContent/', 'createCountryContent')->name('createCountryContent');
            Route::post('createFleetContent/', 'createFleetContent')->name('createFleetContent');
        });

        /** Comments */
        Route::middleware('permission:show-comments|edit-comments|delete-comments|approve-comments')->prefix('comments')->name('comments.')->controller(CommentController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show/{id}/{goToPreview?}', 'show')->name('show');
            Route::post('commentAction/{id}', 'commentAction')->name('commentAction');
            Route::post('reply/{id}', 'reply')->name('reply');
            Route::get('/preview/{id}', 'preview')->name('preview');
        });

        /** Admin's*/
        Route::name('admins.')->prefix('admins')->controller(AdminController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('update_profile', 'update_profile')->name('update_profile');/** */
            Route::post('/generatePassword/{id}', 'generatePassword')->name('generatePassword');
            Route::post('/update/{id}/{update_my_profile?}', 'update')->name('update');
            Route::post('store', 'store')->name('store');
            Route::post('change_status/{id}', 'change_status')->name('change_status_admin');
            Route::post('delete/{id}', 'delete')->name('delete');
        });

        /** Trash  */
        Route::name('trashes.')->controller(TrashController::class)->prefix('trash')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('return_user/{id}', 'return_user')->name('return_user');
        });

        /** Settings */
        Route::name('settings.')->controller(SettingsController::class)->prefix('settings')->group(function () {
            Route::post('store_home_page', 'store_home_page')->name('store_home_page');
            Route::post('store_about_page', 'store_about_us_page')->name('store_about_page');
            Route::post('store_terms', 'store_terms')->name('store_terms');
        });

        /** Slider Services */
        Route::name('sliderServices.')->prefix('slider_services')->controller(SliderServicesController::class)->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('delete/{id}', 'delete')->name('delete');
            Route::post('update/{id}', 'update')->name('update');
        });
        
        /** Fleet Category*/
        Route::name('fleetCategories.')->prefix('fleet_category')->controller(FleetCategoryController::class)->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::get('check_slug/{slug}', 'check_slug')->name('check_slug');
            Route::post('delete/{id}', 'delete')->name('delete');
        });

        /** Fleet */
        Route::name('fleets.')->prefix('fleet')->controller(FleetController::class)->group(function () {
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::delete('delete/{id}', 'delete')->name('delete');
        });

        /** Events */
        Route::name('events.')->prefix('events')->controller(EventsController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::delete('delete/{id}', 'delete')->name('delete');
        });

        /** Reservations */
        Route::name('reservations.')->controller(ReservationController::class)->prefix('reservations')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/show/{id}', 'show')->name('show');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('/store', 'store')->name('store');
            Route::get('/get_customer/{email}', 'get_customer')->name('get_customer'); /*************** created by Qusai ************** */
            Route::get('softDelete/{id}', 'softDelete')->name('softDelete');
            Route::post('get_price/{id}', 'getPrice')->name('getPrice');
            Route::get('/showSoftDelete', 'showSoftDelete')->name('showSoftDelete');
            Route::post('/checkReservationInfo/{isEdit?}', 'checkReservationInfo')->name('checkReservationInfo');

            Route::get('softDeleteRestore/{id}', 'softDeleteRestore')->name('softDeleteRestore');
            Route::get('/export', 'export')->name('export');

            //Ajax routes
            Route::post('/discount', 'discount')->name('discount');
            Route::get('autocomplete', 'autocomplete')->name('autocomplete');
            Route::get('fetch-payment-methods', 'fetchPaymentMethods')->name('fetch-payment-methods');
            Route::get('fetch-customer-ids', 'fetchCustomerIDs')->name('fetch-customer-ids');

        });

       /** Drivers */
        Route::name('drivers.')->controller(DriverController::class)->prefix('drivers')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('softDelete/{id}', 'softDelete')->name('softDelete');

            Route::get('/showSoftDelete', 'showSoftDelete')->name('showSoftDelete');

            Route::get('softDeleteRestore/{id}', 'softDeleteRestore')->name('softDeleteRestore');


        });
        
        /** Customers */
        Route::name('customers.')->controller(CustomerController::class)->prefix('customers')->group(function () {
            Route::get('/', 'index')->name('index');
        });

        /** Service location restriction */
        Route::prefix('service-location-restrictions')->name('serviceLocationRestrictions.')->controller(ServiceLocationRestrictionController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::delete('delete/{id}', 'delete')->name('delete');
            Route::get('check_if_place_available/{lat}/{long}/{radius}/{id?}', 'check_if_place_available')->name('check_if_place_available');
        });

        /** Logs */
        Route::name('logs.')->controller(LogsController::class)->prefix('logs')->group(function () {
            Route::get('/userLogs', 'userLogs')->name('userLogs');
            // Route::get('/documentsLog', 'documentsLog')->name('documentsLog');
            Route::get('/blogLogs', 'blogLogs')->name('blogLogs');
            Route::get('/serviceLogs', 'serviceLogs')->name('serviceLogs');

        });

        /** Coupons */
        Route::name('coupons.')->controller(CouponController::class)->prefix('coupons')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('/store', 'store')->name('store');

            #delete 
            Route::post('softDelete/{id}', 'softDelete')->name('softDelete');
            Route::get('/showSoftDelete', 'showSoftDelete')->name('showSoftDelete');
            Route::get('softDeleteRestore/{id}', 'softDeleteRestore')->name('softDeleteRestore');
        });

        /** Child Seats */
        Route::name('childSeats.')->controller(ChildSeatController::class)->prefix('childSeats')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('/store', 'store')->name('store');
            Route::get('/activeInactiveSingle/{id}', 'activeInactiveSingle')->name('activeInactiveSingle');
        });

        /** Companies */
        Route::name('companies.')->prefix('companies')->controller(CompanyController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::Post('/store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('softDelete/{id}', 'softDelete')->name('softDelete');
            Route::get('/showSoftDelete', 'showSoftDelete')->name('showSoftDelete');
            Route::get('softDeleteRestore/{id}', 'softDeleteRestore')->name('softDeleteRestore');

            /*Ajax*/
            Route::post('/get-states', 'getStates')->name('getStates');
            Route::post('/get-cities', 'getCities')->name('getCities');


        });

        /** Keywords */
        Route::middleware('permission:show-keywords|keywords-banks')->name('keywords.')->controller(TagsController::class)->prefix('keywords')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::get('/get_tags_select', 'get_tags_select')->name('get_tags_select');
            Route::delete('delete/{id}', 'delete')->name('delete');
            Route::get('export_excel', 'export_excel')->name('export_excel');
            Route::post('import_keywords', 'import_keywords')->name('import_keywords');
            Route::post('save_keywords', 'save_keywords')->name('save_keywords');
        });
    
        /** Tags */
        Route::middleware('permission:show-keywords|keywords-banks')->name('tags.')->controller(TagsController::class)->prefix('tags')->group(function () {
            Route::get('/', 'get_tags')->name('index');
            Route::post('/store', 'tag_store')->name('store');
            Route::post('/update/{id}', 'tag_update')->name('update');
            Route::delete('/delete/{id}', 'tag_delete')->name('delete');
        });
    
        /** Faqs */
        Route::prefix('faq')->name('faqs.')->controller(FaqController::class)->group(function () {
            Route::get('create', 'create')->name('create');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::delete('/{id}', 'delete')->name('delete');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('sort', 'sort')->name('sort');
        });
    
        /** Services */
        Route::prefix('services')->name('services.')->controller(ServicesController::class)->group(function () {
            Route::get('/', 'create')->name('create');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('/store', 'store')->name('store');
            Route::post('delete/{id}', 'delete')->name('delete');
            Route::get('preview/{slug}', 'preview')->name('preview');
            Route::get('check-slug/{slug}/{id?}', 'checkSlug')->name('check_slug');
            Route::get('get_paragraph/{id}', 'get_paragraph')->name('get_paragraph');
            Route::post('update_paragraph/{id}', 'update_paragraph')->name('update_paragraph');
            Route::post('/sort', 'sort')->name('sort');
            Route::post('/store-city-id', 'storeCityId')->name('store_city_id');    
        });

        /** Link Mapping */
        Route::name('mapLinking.')->prefix('broken-links')->controller(RedirectMappingController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::delete('delete/{id}', 'delete')->name('delete');
            Route::post('checkUrl', 'checkUrl')->name('checkUrl');
        });
    
        /** Cities */
        Route::name('cities.')->prefix('cities')->controller(CitiesController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('accordingToState/{name}', 'getCityAccordingToState')->name('getCityAccordingToState');
            Route::get('getCityInfo/{id?}', 'getCityInfo')->name('getCityInfo');
        });
    
        /** Countries */
        Route::name('countries.')->prefix('countries')->controller(CountriesController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('states_ajax/{country_id}', 'states')->name('states');
            Route::get('states/{country_id}', 'states_index')->name('states_index');
            Route::get('cities/{state_id}', 'cities_index')->name('cities_index');
            Route::get('companies/{city_id}', 'companies_index')->name('companies_index');
        });
        
        /** States */
        Route::name('states.')->prefix('states')->controller(StateController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('accordingToCountry/{name}', 'getStateAccordingToCountry')->name('getStateAccordingToCountry');
        });
    
        /** Ads */
        Route::name('ads.')->prefix('ads')->controller(AdsController::class)->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
        });
    
        /** Locations routes*/
        Route::name('locations.')->prefix('locations')->controller(LocationController::class)->group(function () {
            Route::get('index', 'index')->name('index');
        });

         /** seo countries*/
         Route::name('seo_countries.')->prefix('seo_countries')->controller(SeoCountriesController::class)->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::get('show/{id}', 'show')->name('show');
            Route::get('delete/{id}', 'delete')->name('delete');
            Route::get('/activeInactiveSingle/{id}', 'activeInactiveSingle')->name('activeInactiveSingle');

        });
        Route::name('seo_cities.')->prefix('seo_cities')->controller(SeoCitiesController::class)->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::get('show/{id}', 'show')->name('show');
            Route::get('check_slug/{slug}', 'check_slug')->name('check_slug');
            Route::get('/activeInactiveSingle/{id}', 'activeInactiveSingle')->name('activeInactiveSingle');
            Route::get('delete/{id}', 'delete')->name('delete');
        });
        
});
