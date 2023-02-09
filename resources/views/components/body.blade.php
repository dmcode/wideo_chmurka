<x-layout>
    <x-slot:title>
        {{ $title ?? config('app.name') }}
    </x-slot>
    <x-header/>
    <main>{{ $slot }}</main>
    <footer>
        <p>Wideo Chmurka. Projekt prostego serwisu do nagrywania pulpitu i publikowania wideo wykonany w technologii PHP 8 (Laravel 9), HTML 5, JavaScript, MongoDB.</p>
    </footer>
    {{ $templates }}
    {{ $scripts }}
</x-layout>
