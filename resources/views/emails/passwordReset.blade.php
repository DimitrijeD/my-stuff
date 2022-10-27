<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css'])
 
</head>
<body>
    <div class="bg-blue-50 p-3 text-gray-600 space-y-6">
        <x-emailHeader :text="$title" />
        

        <p class="">
            {{ __('email.greeting', ['first_name' => '', 'last_name' => '']) }} <br>
            {{ __('email.password_reset.what_is_this', ['email' => $email]) }}
        </p>

        <x-emailBtn :text="__('email.password_reset.btn_txt')" :redirect="$url" />

        <div>
            <p class="italic select-none">{{ __("email.password_reset.if_not_you") }}</p>
            <p class="italic select-none">{{ __('email.email_verification.btn_problem') }}</p>
            <p class="text-blue-500 hover:text-green-500 break-words font-light underline select-all">{{ $url }}</p>
        </div>
                    
        <x-emailFooter />
    </div>
</body>
</html>
