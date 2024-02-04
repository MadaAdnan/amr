<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TripCompletedEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $rating_link;
    private $viewName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($viewName, $rating_link)
    {
        $this->viewName = $viewName;
        $this->rating_link = $rating_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your Ride with Lavishride is Completed")->view($this->viewName, ['rating_link' => $this->rating_link]);
    }
}
