<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->code = $data['code'];
        $this->division = $data['division'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('phumrapee.limpianchop@ivao.aero')
                    ->subject('Confirmation Email')
                    ->view('email.confirm')
                    ->with('division', $this->division)
                    ->with('code', $this->code);
    }
}
