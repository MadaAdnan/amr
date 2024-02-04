<?php

return [
    'default_time_zone' => 'GMT+0',
    'max_hours_durations'=>12,
    'api_max_duration'=>[
       [
        'name'=>'1 Hour',
        'value'=>'1'
       ] ,
       [
        'name'=>'2 Hour',
        'value'=>'2'
       ] ,
       [
        'name'=>'3 Hour',
        'value'=>'3'
       ] ,
       [
        'name'=>'4 Hour',
        'value'=>'4'
       ] ,
       [
        'name'=>'5 Hour',
        'value'=>'5'
       ] ,
       [
        'name'=>'6 Hour',
        'value'=>'6'
       ] ,
       [
        'name'=>'7 Hour',
        'value'=>'7'
       ] ,
       [
        'name'=>'8 Hour',
        'value'=>'8'
       ] ,
       [
        'name'=>'9 Hour',
        'value'=>'9'
       ] ,
       [
        'name'=>'10 Hour',
        'value'=>'10'
       ] ,
       [
        'name'=>'11 Hour',
        'value'=>'11'
       ] ,
       [
        'name'=>'12 Hour',
        'value'=>'12'
       ] 
    ],
    'dashboard_roles'=>['Super-admin','Seo-admin','Seo-specialist','Blogger','Driver'],
    'seo_admin_permitted_roles'=>['Blogger','Seo-specialist','Seo-admin'],
    'roles_admin_can_assign'=>['Blogger', 'Seo-admin', 'Seo-specialist', 'Driver'],
    'support_email'=>'reservation@lavishride.com',
    'sales_email'=>'sales@lavishride.com',
    'support_phone'=>'+1 (281) 717-6449',
    'dashboard_pagination_number'=>10,
    'api_pagination'=>10,
    'categories_max_limit'=>8,
    'front_end_pagination'=>12,
    'blog_front_end_pagination'=>1,
    'front_end_similar_post'=>5,
    'max_attempts_for_email'=>4,
    'van_late_cancel_time'=>168,
    'car_fleets_late_cancel_time'=>24,
    'max_child_seats'=>3,
    'show_correct_blogs_to_role' => [
        'Super-admin'=>[
            'publish',
            'draft',
            'rejected',
            'in-progress',
            'admin_reject'
        ],
        'Seo-admin'=>[
            'publish',
            'draft',
            'rejected',
            'in-progress',
            'admin_reject'
        ],
        'Seo-specialist'=>[
            'publish',
            'rejected',
            'in-progress',
            'admin_reject'
        ],
        'blogger'=>[
            'publish',
            'rejected',
            'in-progress',
            'draft'
        ],
    ],
    'reserving_time_ranges'=>[
        'twentyFourHour'=>24,
        'nineTeenHour'=>19,
        'nineTwelveHour'=>12,
        'sixHour'=>6,
    ],
    'default_pricing'=> (object) [
        'initial_fee'=>20,
        'minimum_hour'=>1,
        'minimum_mile_hour'=>50,
        'minimum_mile'=>50,
        'price_per_hour'=>50,
        'extra_price_per_mile'=>1.6,
        'extra_price_per_mile_hourly'=>1.6
    ],
    'staticWebsitePages' =>
    [
        [
         'url'=>env('APP_URL').'/reservations',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/user/forget_password',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/user/login',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/user/register',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/fleet',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/fleet/business-class-sedan',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/affiliate',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/we-are-hiring-a-licensed-chauffeur',
         'date'=>'2023-07-08'
        ],
        // [
        //  'url'=>route('frontEnd.about_us'),
        //  'date'=>'2023-07-08'
        // ],
        [
         'url'=>env('APP_URL').'/terms-and-conditions',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/privacy-policy',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/faq',
         'date'=>'2023-07-08'
        ],
        [
         'url'=>env('APP_URL').'/contact-us',
         'date'=>'2023-07-08'
        ]
    ],
    'upcoming_status_tap_mobile'=>['accepted','pending','assigned', 'on the way', 'arrived at the pickup location', 'passenger on board'],
    'canceled_status_tap_mobile'=>['late canceled','canceled','failed'],
    'completed_status_tap_mobile'=>['no show','Extra time or mile','completed'],
    'admin_name'=>'LavishRide',
    'hours_duration'=>[
        [
            'id'=>2,
            'name'=>'2 Hour',
            'value'=>2
        ],
        [
            'id'=>3,
            'name'=>'3 Hour',
            'value'=>3
        ],
        [
            'id'=>4,
            'name'=>'4 Hour',
            'value'=>4
        ],
        [
            'id'=>5,
            'name'=>'5 Hour',
            'value'=>5
        ],
        [
            'id'=>6,
            'name'=>'6 Hour',
            'value'=>6
        ],
        [
            'id'=>7,
            'name'=>'7 Hour',
            'value'=>7
        ],
        [
            'id'=>8,
            'name'=>'8 Hour',
            'value'=>8
        ],
        [
            'id'=>9,
            'name'=>'9 Hour',
            'value'=>9
        ],
        [
            'id'=>10,
            'name'=>'10 Hour',
            'value'=>10
        ],
        [
            'id'=>11,
            'name'=>'11 Hour',
            'value'=>11
        ],
        [
            'id'=>12,
            'name'=>'12 Hour',
            'value'=>12
        ]
    ],
    'reporting_message'=>"Thank you for reporting this inconvenience! Your request has been received, and our support team will reply to it within 24 hours.",
    'log_only'=>[
        'id',
        "pick_up_location",
        "drop_off_location",
        "pick_up_date",
        "return_date",
        "pick_up_time",
        "return_time",
        "tip",
        "price",
        "duration",
        "distance",
        "phone_primary",
        "phone_secondary",
        "flight_number",
        "comment",
        "service_type",
        "user_id",
        "driver_id",
        "coupon_id",
        "category_id",
        "company_id",
        "transfer_type",
        "status",
        "deleted_at",
        'fleet_id',
        'child_seats',
        'edit_price',
    ],
    'dashboard_page_types'=>'Home|Services|Fleet Category|About|Help|Orphan|Services Type|Fleet|Countries|Faq|Terms',
   'frontEnd_status'=>['no show', 'canceled', 'late canceled', 'completed'],
   'services_items_nav_bar'=>6,
   'categories_items_nav_bar'=>6,
   'about_the_company'=>'Lavish Ride offers the most luxurious, premium, and comfortable black car service In Houston. It is your reliable best private chauffeur services.',
   'social_media'=>[
    'instagram'=>'https://www.instagram.com/lavish_ride',
    'facebook'=>'https://www.facebook.com/LavishrideUS',
    'twitter'=>'https://twitter.com/lavishride',
    'linkedin'=>'https://www.linkedin.com/company/lavish-ride-us/'
   ],
   'forntend_status'=>['no show', 'canceled', 'late canceled', 'completed'],
   'ios_app_link'=>'https://apps.apple.com/in/app/lavish-ride/id6449616244',
   'company_location'=>'https://www.google.com/maps/place/Lavish+Ride/@29.7723729,-95.4566837,15z/data=!4m6!3m5!1s0x8640d77fa2c78fc9:0xca939ddf63707654!8m2!3d29.7723729!4d-95.4566837!16s%2Fg%2F11g6btlspc?hl=en&entry=ttu'

];