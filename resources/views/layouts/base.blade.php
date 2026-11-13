<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trim($__env->yieldContent('title')) !== '' ? trim($__env->yieldContent('title')).' | RentCampy' : 'RentCampy — Sewa Perlengkapan Camping' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-slate-900 antialiased">
    @yield('body')

    @php
        $flash = [
            'success' => session('success'),
            'error' => session('error'),
            'warning' => session('warning'),
            'toast' => session('toast'),
        ];
    @endphp
    <script type="application/json" id="flash-data">{!! json_encode($flash, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
</body>
</html>
