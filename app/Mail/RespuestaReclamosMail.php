<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RespuestaReclamosMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $htmlContent = "
            <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { margin: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
                        .header { font-size: 1.5em; font-weight: bold; color: #007BFF; }
                        .details { margin-top: 10px; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>Esta es la respuesta a su reclamo</div>
                        <div class='details'>
                            <p><strong>Fecha del reclamo:</strong> {$this->data['fecha_reclamo']}</p>
                            <p><strong>Respuesta:</strong> {$this->data['respuesta']}</p>
                        </div>
                        <p>Gracias por contactarnos. Estamos aquí para ayudarle.</p>
                    </div>
                </body>
            </html>
        ";

        return $this->html($htmlContent);
    }
}
