<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User as User;

class SendTip extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subject;
    public $body;
    public $view;
    public $league;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$subject,$body,$view,$league="")
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->body = $body;
        $this->view = $view;
        $this->league = $league;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->user->email)->subject($this->subject)->view($this->view);
    }
}
