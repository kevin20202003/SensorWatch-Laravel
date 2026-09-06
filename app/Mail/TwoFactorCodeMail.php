<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $codigo;

    public function __construct(string $nombre, string $codigo)
    {
        $this->nombre = $nombre;
        $this->codigo = $codigo;
    }

    public function build(): static
    {
        return $this->subject('Tu código de verificación de SensorWatch')
            ->view('emails.two-factor-code');
    }
}
