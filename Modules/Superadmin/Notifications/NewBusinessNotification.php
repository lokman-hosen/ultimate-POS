<?php

namespace Modules\Superadmin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBusinessNotification extends Notification
{
    use Queueable;

    protected $business;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($business)
    {
        $this->business = $business;
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
        $details = __('mail.new_business_details', [
            'business' => $this->business->name,
            'owner' => $this->business->owner->user_full_name,
            'email' => $this->business->owner->email,
            'phone' => $this->business->locations->first()->mobile,
        ]);

        $mail = (new MailMessage)
                ->subject(__('mail.new_business_subject'))
                ->greeting(__('mail.hello'))
                ->line(__('mail.new_business_intro'))
                ->line($details);

        //Same document the owner receives with the welcome email
        $attachment = base_path(NewBusinessWelcomNotification::ATTACHMENT_PATH);
        if (file_exists($attachment)) {
            $mail->attach($attachment, ['mime' => 'application/pdf']);
        } else {
            \Log::error('New business email attachment not found: '.NewBusinessWelcomNotification::ATTACHMENT_PATH);
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
