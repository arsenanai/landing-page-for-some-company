<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;
    public $fio, $phone, $powta, $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fio,$phone,$powta,$text)
    {
        $this->fio = $fio;
        $this->phone = $phone;
        $this->powta = $powta;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sender = new Sender();
        $sender->email = $this->powta;
        $sender->name = $this->fio;
        return $this
            ->from($sender)
            ->subject('[Оставить отзыв] '.$this->fio.' '.$this->phone)
            ->view('mail.contact-us');
    }
}

class Sender{
    public $email, $name;
}
