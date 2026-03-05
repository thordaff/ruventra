<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/js/app.ts'])
        @if(isset($page))
            @inertiaHead
        @endif
        <style>
            html { background-color: oklch(1 0 0); }
            html.dark { background-color: oklch(0.145 0 0); }
            .flip-card { perspective: 1000px; }
            .flip-card-inner { transition: transform 0.6s; transform-style: preserve-3d; position: relative; width: 100%; min-height: 350px; }
            .flip-card-front, .flip-card-back { background: #fff; padding: 2rem; position: absolute; width: 100%; backface-visibility: hidden; min-height: 350px; box-shadow: 0 2px 16px #0002; }
            .flip-card-back { transform: rotateY(180deg); }
        </style>
    </head>
    <body class="font-sans antialiased">
        @include('components.navbar')
        <main style="min-height:80vh;padding:2rem 0 4rem 0;text-align:center;"></main>
        @include('components.modal')
        @include('components.footer')
    </body>
</html>
