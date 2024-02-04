<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TripEmail extends Mailable
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
        return $this->view($this->viewName, ['details' => $this->details])
                    ->cc(config('general.support_email'), 'lavishride')
                    ->replyTo(config('general.support_email'), 'lavishride')
                    ->subject('Lavish Ride');
    }
}
