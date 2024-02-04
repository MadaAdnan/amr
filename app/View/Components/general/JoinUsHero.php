<?php

namespace App\View\Components\general;

use Illuminate\View\Component;

class JoinUsHero extends Component
{
    /**
     * Join Us Hero Component.
     *
     * doc: show the here section for join us type pages
     * 
     * @return void
     */

    public $title;
    public $slogan;
    public $description;
    public $image;
    public $buttonActionLink;
    public $buttonText;

    public function __construct($title , $slogan,$description,$image,$buttonActionLink,$buttonText)
    {
        $this->title = $title;
        $this->slogan = $slogan;
        $this->description = $description;
        $this->image = $image;
        $this->buttonActionLink = $buttonActionLink;
        $this->buttonText = $buttonText;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.general.join-us-hero');
    }
}
