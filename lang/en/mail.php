<?php

/*
| Email texts (password reset, registration). Only English and Spanish are
| maintained; other locales fall back to English.
*/

return [
    'hello' => 'Hello!',
    'whoops' => 'Whoops!',
    'regards' => 'Regards,',
    'all_rights_reserved' => 'All rights reserved.',
    'trouble_clicking' => 'If you are having trouble clicking the ":actionText" button, copy and paste the URL below into your web browser:',
    'reset_password_subject' => ':app - Reset your password',
    'reset_password_intro' => 'You are receiving this email because we received a password reset request for your account.',
    'reset_password_button' => 'Reset password',
    'reset_password_expire' => 'This password reset link will expire in :count minutes.',
    'reset_password_ignore' => 'If you did not request a password reset, no further action is required.',
    'welcome_subject' => 'Welcome to :app',
    'welcome_body' => '<p>Hello {owner_name},</p><p>Thank you for registering {business_name} with :app. Your account is ready and you can sign in with your username or email.</p><p>Regards,<br>The :app team</p>',
    'new_business_subject' => 'New business registration',
    'new_business_intro' => 'A new business has registered.',
    'new_business_details' => 'Business: :business, owner: :owner, email: :email, contact number: :phone',

    // Welcome email on business sign-up (with document to fill in)
    'yaigo_welcome_subject' => 'Welcome to Yaigo! Your registration is complete',
    'yaigo_welcome_greeting' => 'Hello :name,',
    'yaigo_welcome_success' => 'Thank you for registering **:business** in Yaigo. Your account has been created successfully.',
    'yaigo_welcome_attachment' => 'We have attached a document you need to complete. Please fill it in, sign it and send it back to us.',
    'yaigo_welcome_send_back' => 'You can send it to **:email** (or simply reply to this email).',
    'yaigo_welcome_username' => 'Your username: **:username**',
    'yaigo_welcome_login' => 'Sign in to Yaigo',
    'yaigo_welcome_questions' => 'If you have any questions, just reply to this email.',
    'yaigo_welcome_team' => 'The Yaigo team',
];
