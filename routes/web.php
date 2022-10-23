<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/test/email', function (){
//     return view('emails.verifyMail', [
//         'url' => "http://localhost/email-verification/uid/32/c/EGG3kLbdTdytX5GzZekvhDlt3Z0bL43l0LBgN8DC698Nggse0Ei1hqhCzvo7uPhH",

//     ]);
// });

Route::get('/test', function () {
    broadcast(new \App\Events\TestEvent('test PLZS'));
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
