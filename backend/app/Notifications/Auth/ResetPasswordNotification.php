<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificación de reset que apunta al frontend SPA, no a la ruta web.
 */
final class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = config('app.frontend_url') . '/restablecer?token=' . $this->token
            . '&email=' . urlencode((string) $notifiable->getEmailForPasswordReset());

        return (new MailMessage())
            ->subject('Restablecimiento de contraseña — Alcaldía Distrital de Santa Marta')
            ->greeting('Hola ' . ($notifiable->name ?? 'usuario'))
            ->line('Recibimos una solicitud de restablecimiento de contraseña.')
            ->action('Restablecer contraseña', $url)
            ->line('Si no solicitó este cambio, ignore este correo.')
            ->line('Este enlace expira en ' . config('auth.passwords.users.expire', 60) . ' minutos.');
    }
}
