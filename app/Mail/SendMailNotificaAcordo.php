<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailNotificaAcordo extends Mailable
{
    use Queueable, SerializesModels;
    public $dados;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dados)
    {
        $this->dados = $dados;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Notificação Situação Processual!');
        $this->to(
        [   'victorhugoraj@gmail.com',
            'maria.fernanda@francoguimaraes.adv.br',
            'luciana.santos@francoguimaraes.adv.br',
            'tiago.oliveira@francoguimaraes.adv.br',
            'contato@francoguimaraes.adv.br'
        ]);
        return $this->markdown('email.notifica')->with([
            'dados' => $this->dados,
        ]);
    }
}
