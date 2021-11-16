<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="/manifest.json">
    <meta name="description" content="CAA Sydney Chapter">
    <meta name="author" content="Eddy Ella">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('/assets/css/app.min.css') }}">
    <meta name="theme-color" content="#168C6A">
    <meta name="msapplication-TileColor" content="#168C6A">
    <meta name="apple-mobile-web-app-status-bar-style" content="#168C6A">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    @yield('head')
</head>
<body>

<div id="app">
    @include('errors.list')
    <div class="container-fluid">
        @include('front.includes.first-nav')
        @include('front.includes.nav',
            ['fixed' => false, 'shadow' => false]
        )
        @include('front.includes.menu')
    </div>

    @yield('body')
    @include('front.includes.footer')
</div>
<script src="https://unpkg.com/@ionic/core@0.0.2-30/dist/ionic.js"></script>
<script src="{{ asset('/assets/js/app.min.js') }}"></script>
@yield('js')
</body>
</html>
