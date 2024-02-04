<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FleetCard extends Component
{
    /**
     * Fleet Card Component.
     * 
     * doc: card will show for the fleetCategory items
     *
     * @param App\Models\FleetCategory $item
     * - String $image the image link
     * - String $slug to add it to the show button 
     * - Boolean $isFlightTracking boolean value to show the fleet category support flight tracking or not
     * - String $title title for the fleet category
     * - String $description description for the fleet category
     * - Number $passengers number of passengers the fleet category support
     * - Number $luggages number of luggages the fleet category support
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
        return view('components.blog.fleet-card');
    }
}
