<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MensajeRecibidoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Nuevo mensaje recibido';
    public $mensaje;
    public $device;
    public $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $device,$content)
    {
        $this->mensaje = $message;
        $this->device = $device;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.mensaje');
    }
}
