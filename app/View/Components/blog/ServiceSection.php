<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ServiceSectionComponent extends Component
{
   
    /**
     * Service section component.
     * 
     * doc: for the service section in the front end
     *
     * @return void
     */

     public $image; #section image
     public $title; #section title
     public $description; #section description
     public $imagePlace; #the place of the image if it's right or left

    public function __construct($image , $title,$description , $imagePlace)
    {
        $this->image = $image;
        $this->title = $title;
        $this->description = $description;
        $this->imagePlace = $imagePlace;
    }


    public function render()
    {
        return view('components.blog.service-section');
    }
}
