<?php

namespace App\View\Components\general;

use Illuminate\View\Component;

class ApplicationTitle extends Component
{
    /**
     * Application Title.
     *
     * @return void
     */

     public $title;
     
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.general.application-title');
    }
}
