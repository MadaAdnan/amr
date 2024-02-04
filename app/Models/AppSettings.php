<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class AppSettings
 *
 * This model represents application settings related to the front end.
 * AppSettings may include text and value pairs, as well as media assets
 * like images for sliders and icons.
 *
 * Why this model exists:
 * - To store and manage front-end related settings in the application.
 *
 * @package App\Models
 */



class AppSettings extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'text',
        'value'
    ];

    protected $append = [
        'sliderOne',
        'sliderTwo',
        'iconOne',
        'iconTwo',
        'iconThree',
        'iconFour',
        'iconFive',
        'iconSix',
    ];


    public function getSliderOneAttribute()
    {
        return $this->getFirstMediaUrl('home_page_slider_image_one');
    }

    public function getSliderTwoAttribute()
    {
        return $this->getFirstMediaUrl('home_page_slider_image_two');
    }

    public function getIconOneAttribute()
    {
        return $this->getFirstMediaUrl('what_makes_us_icon_one');
    }

    public function getIconTwoAttribute()
    {
        return $this->getFirstMediaUrl('what_makes_us_icon_two');
    }

    public function getIconThreeAttribute()
    {
        return $this->getFirstMediaUrl('what_makes_us_icon_three');
    }

    public function getIconFourAttribute()
    {
        return $this->getFirstMediaUrl('what_makes_us_icon_four');
    }

    public function getIconFiveAttribute()
    {
        return $this->getFirstMediaUrl('what_makes_us_icon_five');
    }
    
    public function getIconSixAttribute()
    {
        return $this->getFirstMediaUrl('what_makes_us_icon_six');
    }

}
