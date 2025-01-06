<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Recuperação de Senha')
        ->line('Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha.')
        ->action('Redefinir Senha', route('password.reset', ['token' => $this->token, 'email' => $notifiable->email]))
        ->line('Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.');
}
}
