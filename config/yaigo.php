<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Yaigo sender
    |--------------------------------------------------------------------------
    |
    | Sender used for Yaigo emails to business owners (e.g. the welcome email
    | on sign-up). Does not change the global mail configuration.
    |
    */
    'mail' => [
        'from_address' => env('YAIGO_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'from_name' => env('YAIGO_MAIL_FROM_NAME', 'Yaigo'),
        //Address the customer sends the completed document to (falls back to the sender)
        'reply_to' => env('YAIGO_MAIL_REPLY_TO', env('YAIGO_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS'))),
    ],

    /*
    |--------------------------------------------------------------------------
    | Welcome email on business sign-up
    |--------------------------------------------------------------------------
    |
    | Sent to the owner after a business is created from the website or from
    | the Superadmin panel, with a document attached that the customer has to
    | fill in and send back. To use a new version of the document replace the
    | file or point YAIGO_WELCOME_ATTACHMENT to it (relative to the project root
    | or absolute). YAIGO_WELCOME_EMAIL_ENABLED=false switches the email off.
    |
    */
    'welcome_email_enabled' => env('YAIGO_WELCOME_EMAIL_ENABLED', true),

    'welcome_attachment' => [
        'path' => env('YAIGO_WELCOME_ATTACHMENT', 'resources/documents/YAIGO_customer_agreement.pdf'),
        //File name the customer sees in the email
        'name' => env('YAIGO_WELCOME_ATTACHMENT_NAME', 'YAIGO_customer_agreement.pdf'),
    ],
];
