<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TripEdit extends Mailable
{
    use Queueable, SerializesModels;

    private $details;
    private $viewName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($viewName, $details)
    {
        $this->viewName = $viewName;
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->details['success'] ? "Your Ride with Lavishride Was Updated" : "Trip number #" . $this->details['trip']->id ." status changed";
        return $this->subject($title)->view($this->viewName, ['details' => $this->details]);
    }
}
