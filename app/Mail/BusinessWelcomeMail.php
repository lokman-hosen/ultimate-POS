<?php

namespace App\Mail;

use App\Business;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Spanish welcome email sent to the owner of a newly created business,
 * with the document the customer has to fill in and send back.
 */
class BusinessWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $businessId;

    public $ownerId;

    /**
     * IDs instead of models so the mail can be queued later without changes.
     */
    public function __construct($businessId, $ownerId)
    {
        $this->businessId = $businessId;
        $this->ownerId = $ownerId;
        $this->locale('es');
    }

    public function envelope(): Envelope
    {
        $reply_to = config('yaigo.mail.reply_to');

        return new Envelope(
            from: new Address(config('yaigo.mail.from_address'), config('yaigo.mail.from_name')),
            replyTo: ! empty($reply_to) ? [new Address($reply_to, config('yaigo.mail.from_name'))] : [],
            subject: __('mail.yaigo_welcome_subject', [], 'es'),
        );
    }

    public function content(): Content
    {
        $business = Business::findOrFail($this->businessId);
        $owner = User::findOrFail($this->ownerId);

        return new Content(
            markdown: 'emails.business_welcome',
            with: [
                'owner_name' => trim($owner->first_name.' '.$owner->last_name),
                'business_name' => $business->name,
                'username' => $owner->username,
                'login_url' => route('login', ['lang' => 'es']),
                'has_attachment' => self::attachmentPath() !== null,
                'send_to' => config('yaigo.mail.reply_to'),
            ],
        );
    }

    public function attachments(): array
    {
        $path = self::attachmentPath();
        if ($path === null) {
            return [];
        }

        return [
            Attachment::fromPath($path)
                ->as(config('yaigo.welcome_attachment.name'))
                ->withMime('application/pdf'),
        ];
    }

    /**
     * Absolute path of the configured attachment, or null when the file is missing
     *
     * @return string|null
     */
    public static function attachmentPath()
    {
        $path = config('yaigo.welcome_attachment.path');
        if (empty($path)) {
            return null;
        }
        if (! str_starts_with($path, '/')) {
            $path = base_path($path);
        }

        return is_file($path) && is_readable($path) ? $path : null;
    }
}
