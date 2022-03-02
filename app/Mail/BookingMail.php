<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $bookingdata;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bookingdata)
    {
        $this->bookingdata = $bookingdata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.booking')->with('data', $this->bookingdata);
    }
}
