<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Image URL.
     *
     * @var imageUrl
     */
    public $imageUrl;

    public $imageName;

 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($imageUrl, $imageName)
    {
        $this->imageUrl = $imageUrl;
        $this->imageName = $imageName;
    }
 

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.imageMail')
        ->subject("SelfieStand fényképed")
            ->with([
                'imageUrl' => $this->imageUrl,
                'imageName' => $this->imageName
            ]);
    }
}
