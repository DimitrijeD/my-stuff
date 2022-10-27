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
    ]

];
