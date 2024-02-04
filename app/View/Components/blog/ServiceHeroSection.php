<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ServiceHeroSectionComponent extends Component
{
    /**
     * Service hero section.
     * 
     * doc: hero section for the services
     *
     * @return void
     */

     public $title;
     public $description;
 
    
    public function __construct($title,$description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.blog.service-hero-section');
    }
}
