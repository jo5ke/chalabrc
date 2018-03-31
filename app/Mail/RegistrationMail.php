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

    protected $subject;
    protected $view;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$subject,$view)
    {
        //
        $this->user = $user;
        $this->subject = $subject;
        $this->view = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       // return $this->view('view.name');
        return $this->from("mg.breddefantasy@office.com")->subject($this->subject)->view($this->view);
    }
}
