@props(['text', 'redirect'])

<a href="{{ $redirect }}" class="block w-full p-4 px-6 text-center select-none rounded-xl bg-blue-500 hover:bg-green-400 text-gray-100 hover:text-white text-xl focus:outline-none">
    {{ $text }}
</a>