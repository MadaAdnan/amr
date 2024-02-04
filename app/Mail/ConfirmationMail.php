<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $password;
    protected $confirmationToken;
    protected $customer;

    public function __construct($password, $confirmationToken,$customer)
    {
        $this->password = $password;
        $this->confirmationToken = $confirmationToken;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $password = $this->password;
        $token = $this->confirmationToken;
        $customer = $this->customer;
        return $this->view('emails.confirmation_mail',compact('password','token','customer'));
    }
}
