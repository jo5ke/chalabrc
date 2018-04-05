<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User as User;
use App\PrivateLeague as PrivateLeague;

class LeagueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $view;
    public $sender;
    public $receiver;
    public $pl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $sender,User $receiver,PrivateLeague $pl,$subject,$view)
    {
        //
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->pl = $pl;
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
        return $this->from("mg.breddefantasy@gmail.com")->subject($this->subject)->view($this->view);
    }
}
