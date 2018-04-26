<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User as User;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $view;
    public $user;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$subject,$view,$token="")
    {
        //
        $this->user = $user;
        $this->subject = $subject;
        $this->view = $view;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       // return $this->view('view.name');
        return $this->from("support@breddefantasy.com")->subject($this->subject)->view($this->view);
    }
}
