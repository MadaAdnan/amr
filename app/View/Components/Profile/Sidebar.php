<?php

namespace App\View\Components\Profile;

use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Sidebar
     * 
     * doc: the side bar for the user profile
     * 
     *
     * @return void
     */
    
    public $activeNavItem; // select what item is active in the sidebar

    public function __construct($activeNavItem)
    {
        $this->activeNavItem = $activeNavItem;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.profile.sidebar');
    }
}
