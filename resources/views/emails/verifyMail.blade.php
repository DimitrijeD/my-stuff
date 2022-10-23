<!DOCTYPE html>
<html>
<head>
    <title>Email Validation for {{ config('app.name') }} Account</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css'])
 
</head>
<body>

    <div class="grid place-items-center h-screen">
        <div class="w-3/4 border-2 border-gray-200 space-y-4">

            <p class="text-center text-blue-500 py-6 text-2xl font-medium">
                Email Validation for <a href="{{ url('/') }}" target="_blank" class=" font-semibold text-sky-500 hover:text-sky-600">{{ config('app.name') }}</a> Account
            </p>


            <div class="flex select-none ">
                <a 
                    href="{{ $url }}" 
                    class=" text-center mx-auto py-4 px-6 bg-blue-500 hover:bg-green-400 text-gray-100 hover:text-white text-xl font-semibold focus:outline-none"
                >
                    Click here to verify your account.
                </a>
            </div>


            <div class="italic p-3 ">
                <p class="mb-3 text-gray-500 select-none">Having issues with the button? Copy link:</p>
                <p class=" text-blue-500 hover:text-green-500 break-words font-light text-sm underline select-all">{{ $url }}</p>
            </div>
        </div>
    </div>

</body>
</html>
