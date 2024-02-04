<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Blog extends Component
{
    /**
     * Blog Component.
     * 
     * doc:Blog Component For FrontEnd
     *
     * @return void
     */

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.blog.blog');
    }
}
