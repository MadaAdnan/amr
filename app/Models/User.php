<?php

namespace App\Models;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\PersonalAccessTokenFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * Class User
 *
 * This model represents users in the application. Users can have various roles such as Admin, Company, Driver, Dispatcher, and User.
 * It includes attributes like first_name, last_name, email, birthdate, phone, avatar, etc.
 * The model also includes relationships with other models such as roles, driver, dispatcher, trips, bills, etc.
 *
 * Why this model exists:
 * - To store and manage user-related data in the application.
 * - Provides a structured way to interact with user data in the database.
 *
 * @package App\Models
 */



class User extends Authenticatable implements HasMedia ,MustVerifyEmail
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable , SoftDeletes , InteractsWithMedia, LogsActivity;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                "id",
                'first_name',
                'last_name',
                'email',
            ]); 
    }

    #constants
    const DEACTIVATED = 1;
    const ACTIVE = 0;
    
    protected $guarded = [];
    protected $guard_name = 'web';
    
   protected $fillable = [
       'first_name',
       'last_name',
       'email',
       'phone',
       'email_verified_at',
       'phone_verified_at',
       'two_factor_secret',
       'user_id',
       'is_deactivated',
       'stripe_id',
       'local_pin',
       'password',
       'fcm',
       'country_code',
       'provider', 
       'social_id'
   ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $append = [
        'full_name',
        'image',
        'roleName',
        'phone_number_with_country_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function bills()
    {
        return $this->hasMany(NewBill::class);
    }
    
    public function otp()
    {
        return $this->hasOne(OtpPass::class);
    }

    public function Blogger()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;    
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'user_id');
    }

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function getPhoneNumberWithCountryCodeAttribute()
    {
        return $this->country_code.$this->phone;
    }

    public function new_bills()
    {
        return $this->hasMany(NewBill::class,'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class,'user_id');
    }
    public function reservationDrivers()
    {
        return $this->hasMany(Reservation::class,'driver_id');
    }

    public function getCustomerId()
    {
        $customer = Customer::where('email', $this->email)->first();
        return $customer->id;
    }

    public function createToken($name, array $scopes = []){

        $this->tokens->each(function ($token) {
            $token->delete();
        });
        return Container::getInstance()->make(PersonalAccessTokenFactory::class)->make(
            $this->getKey(), $name, $scopes
        );
    }

    public function notificationSetting()
    {
        return $this->hasOne(NotificationSetting::class,'user_id');
    }

    public function getRoleNameAttribute()
    {
        return $this->roles[0]->name;
    }

    public function hasPermission($permission)
    {
        // Your implementation for checking permissions
        return true;
    }
}
