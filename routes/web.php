<?php

use Illuminate\Support\Facades\Route;


// Route::get('email-verification', function (){
//     return view('emails.verifyEmail', [
//         'url' => "http://localhost/email-verification/uid/32/c/EGG3kLbdTdytX5GzZekvhDlt3Z0bL43l0LBgN8DC698Nggse0Ei1hqhCzvo7uPhH",
//         'title' => __('email.email_verification.title', ['appName' => config('app.name')]),
//         'email' => 'qwe@qwe',
//         'first_name' => "SAJjaskd",
//         'last_name' => "SAJjaskd",
//     ]);
// });

// Route::get('reset-password', function (){
//     return view('emails.passwordReset', [
//         'url' => "http://localhost/reset-password?email=qwe@qwe&c=k106BgNGIdpDpKml7Ty8gSUwTON9Csqn3AbZpYWyWl6bhHI5M1OMP4b18bDUMdhO",
//         'email' => 'qwe@qwe',
//         'title' => __('email.password_reset.title', ['appName' => config('app.name')]),
//     ]);
// });

Route::get('/{any}', function () {
    return view('welcome');
})->name('home')->where('any', '.*');
