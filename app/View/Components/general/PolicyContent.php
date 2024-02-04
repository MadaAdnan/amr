<?php

namespace App\View\Components\general;

use Illuminate\View\Component;

class PolicyContent extends Component
{
    /**
     * Policy Content
     *
     * doc: the content for policy pages
     * 
     * @return void
     */
     public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.general.policy-content');
    }
}
