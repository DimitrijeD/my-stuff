<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'sent' => 'We have emailed your password reset link!',
    'throttled' => 'Please wait before retrying.',
    'token' => 'This password reset token is invalid.',
    'user' => "We can't find a user with that email address.",

    /*
    |----------------------------------------
    | ResetPassword
    |----------------------------------------
    */
    'VE001' => 'VE001 Incorrect data. Please make sure you have opened latest Password Reset Email.', // Token not provided
    'VE002' => 'VE002 Incorrect data. Please make sure you have opened latest Password Reset Email.', // Token digits mismatch
    'VE003' => 'VE003 Incorrect data. Please make sure you have opened latest Password Reset Email.', // Email missing
    'VE004' => 'VE004 Incorrect data. Please make sure you have opened latest Password Reset Email.', // Email is not email
    'VE005' => 'VE005 Incorrect data. Please make sure you have opened latest Password Reset Email.', // Email is not string
    'VE006' => 'VE006 Incorrect data. Please make sure you have opened latest Password Reset Email.', // User with email doesn't exist.
    'VE007' => 'VE007 Incorrect data. Please make sure you have opened latest Password Reset Email.', // Stored token doesn't match user's token.
    'success_reset_authenticated'   => "Your password has been reset! Don't forget to login next time with new password.",
    'success_reset_unathenticated' => "Your password has been reset! You can now login with new password.",

    
];
