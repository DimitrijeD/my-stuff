<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General email lines
    |--------------------------------------------------------------------------
    */

    'greeting' => 'Hello :first_name :last_name,',
    'regards' => 'Best regards,',

    'password_reset' => [
        'title'        => 'Password reset for :appName account',
        'what_is_this' => 'Password reset has been requested for your :email account.',
        'btn_txt'      => 'Click here to reset password.',
        'if_not_you'   => "If you didn't request password reset, you can ignore this email.",
    ],
    
    'email_verification' => [
        'title'        => 'Email verification for :appName account',
        'what_is_this' => 'You have created account with this :email address. Please click on following link to verify you accound and unlock all features our app provides.',
        'btn_txt'      => 'Click here to verify account',
        'btn_problem'  => "Having issues with the button? Open following link:",

        'requires_login' => 'You must be logged in in order to verify account.',
        'email_dispatched' => 'Email has been sent. Check your inbox.',
        'another_email_dispatched' => "We have sent another password reset email to :email . Only latest email is valid. If you can't see email, make sure you inserted correct email address. Also check your inbox, spam and trash or request another one.",
        'pending_verification' => 'Please verify your account.',
        'successfully_validated' => 'Account validated.',

        'no_user_danger' => 'Something went wrong. Did you open correct email?',
        'incorrect_hash' => "Something went wrong. Did you open correct email? Only latest email is valid. Check your inbox, spam and trash for latest email try requesting another.",
        
    ]

];
