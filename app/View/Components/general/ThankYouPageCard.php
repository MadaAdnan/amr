<?php

namespace App\View\Components\general;

use Illuminate\View\Component;

class ThankYouPageCard extends Component
{
    /**
     * Thank you page card.
     *
     * doc: Thank you page card show after successful submission
     * 
     * @return void
     */

     public $image;
     public $title;
     public $description;

    public function __construct($image,$title,$description)
    {
        $this->image = $image;
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
        return view('components.general.thank-you-page-card');
    }
}
