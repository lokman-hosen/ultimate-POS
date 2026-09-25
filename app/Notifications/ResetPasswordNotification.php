<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Password reset email, sent in the user's language (English or Spanish).
 * Only the button carries the link; the plain-text copy of the URL is left out.
 */
class ResetPasswordNotification extends ResetPassword
{
    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        $message = (new MailMessage)
            ->subject(__('mail.reset_password_subject', ['app' => config('app.name')]))
            ->greeting(__('mail.hello'))
            ->line(__('mail.reset_password_intro'))
            ->action(__('mail.reset_password_button'), $url)
            ->line(__('mail.reset_password_expire', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(__('mail.reset_password_ignore'));

        //Only the button carries the link (see vendor/notifications/email.blade.php)
        $message->viewData['hide_action_url'] = true;

        return $message;
    }
}
