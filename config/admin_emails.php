<?php

return [

    'send_admin_emails' => env('SEND_ADMIN_EMAILS', true),

    'use_local_admin_email' => env(
        'USE_LOCAL_ADMIN_EMAIL',
        true
    ),

    'admin_email' => env(
        'USE_LOCAL_ADMIN_EMAIL',
        true
    )
        ? env('ADMIN_EMAIL_LOCAL')
        : env('ADMIN_EMAIL_LIVE'),

];