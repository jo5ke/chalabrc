<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Newsletter as Newsletter;
use App\User as User;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $view;
    public $user;
    public $newsletter;

    //////////newsletters attributes
    // public $title;
    // public $text;

    // public $title1;
    // public $h1;
    // public $text1;
    // public $image1;

    // public $title2;
    // public $h2;    
    // public $text2;
    // public $image2;
    
    // public $title3;
    // public $h3;        
    // public $text3;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$subject,$view,Newsletter $newsletter)
    {
        //
        $this->user = $user;
        $this->subject = $subject;
        $this->view = $view;
        $this->newsletter = $newsletter;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("support@breddefantasy.com")->subject($this->subject)->view($this->view);
    }
}
