<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'forgot_password_email' => [
        'success' => "We have sent password reset email to :email . Make sure you inserted correct email address. Check your inbox, spam and trash. If you can't see email, request another one.",
        'fake_success' => "We have sent password reset email to :email . Make sure you inserted correct email address. Check your inbox, spam and trash. If you can't see email, request another one.",
        'update' => "We have sent another password reset email to :email . Only latest code is valid. If you can't see email, make sure you inserted correct email address. Also check your inbox, spam and trash or request another one.",
        'forgot_password_email.max_requests_exceeded' => "You have requested too many emails. We will no longer send password reset emails to :email .",
    
    ],
    
];
