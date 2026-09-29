<?php

namespace Modules\Superadmin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBusinessWelcomNotification extends Notification
{
    use Queueable;

    //Owners send the completed document back to this address
    const REPLY_TO = 'info@yaigo.es';

    //Document the owner has to fill in, attached to the welcome email
    const ATTACHMENT_PATH = 'resources/documents/YAIGO_customer_agreement.pdf';

    protected $email_data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email_data)
    {
        $this->email_data = $email_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail_data = $this->email_data;

        $mail = (new MailMessage)
                    ->subject($mail_data['subject'])
                    ->replyTo(self::REPLY_TO)
                    ->view(
                        ['emails.plain_html', 'emails.plain_text'],
                        ['content' => $mail_data['body']]
                    );

        //A missing document must not stop the welcome email
        if (file_exists(base_path(self::ATTACHMENT_PATH))) {
            $mail->attach(base_path(self::ATTACHMENT_PATH), ['mime' => 'application/pdf']);
        } else {
            \Log::error('Welcome email attachment not found: '.self::ATTACHMENT_PATH);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
