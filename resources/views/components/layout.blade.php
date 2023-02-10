<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>{{ $title ?? config('app.name') }}</title>
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="/images/logo.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/images/logo.svg">
        <link rel="stylesheet" href="/assets/fontawesome-free-6.1.2-web/css/all.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500&family=Pacifico&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        {{ $slot }}
    </body>
</html>
