<?php

namespace App\Services;

use App\Business;
use App\Mail\BusinessWelcomeMail;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Single entry point for the sign-up welcome email, used by the public
 * registration and the Superadmin "add business" screen.
 * A failure here never affects the registration itself.
 */
class BusinessWelcomeMailer
{
    public function send(Business $business, User $owner): void
    {
        try {
            if (! config('yaigo.welcome_email_enabled')) {
                return;
            }

            if (empty($owner->email) || ! filter_var($owner->email, FILTER_VALIDATE_EMAIL)) {
                Log::warning('Yaigo welcome email not sent: owner has no valid email', ['business_id' => $business->id, 'owner_id' => $owner->id]);

                return;
            }

            if (BusinessWelcomeMail::attachmentPath() === null) {
                Log::error('Yaigo welcome email: attachment not found, sending without it', ['path' => config('yaigo.welcome_attachment.path')]);
            }

            Mail::to($owner->email)->send(new BusinessWelcomeMail($business->id, $owner->id));

            Log::info('Yaigo welcome email sent', ['business_id' => $business->id, 'email' => $owner->email]);
        } catch (\Throwable $e) {
            Log::error('Yaigo welcome email failed', ['business_id' => $business->id ?? null, 'message' => $e->getMessage()]);
        }
    }
}
