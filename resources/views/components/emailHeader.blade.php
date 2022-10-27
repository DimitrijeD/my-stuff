@props(['text'])

<header class="text-center text-blue-500 hover:text-blue-600 py-6 text-2xl ">
    <a href="{{ url('/') }}" target="_blank" class="font-semibold ">
        {{ $text }}
    </a>
</header>
 
