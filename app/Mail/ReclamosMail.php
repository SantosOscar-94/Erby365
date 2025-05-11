<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReclamosMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    /**
     * Create a new message instance.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        // Generar la URL dinámica basada en el ID del reclamo
        $this->url = url("/form-reclamos/{$id}");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Retornar el correo en formato HTML
        return $this->html("
            <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { margin: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
                        .header { font-size: 1.5em; font-weight: bold; color: #007BFF; }
                        .details { margin-top: 10px; }
                        a { color: #007BFF; text-decoration: none; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>Su reclamo fue enviado con éxito</div>
                        <div class='details'>
                            <p>Puede ver los detalles accediendo al siguiente enlace:</p>
                            <p><a href='{$this->url}'>Ver Reclamo</a></p>
                        </div>
                        <p>Gracias por contactarnos. Estamos aquí para ayudarle.</p>
                    </div>
                </body>
            </html>
        ");
    }
}
