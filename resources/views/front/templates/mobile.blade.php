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
    <link rel="stylesheet" href="{{ mix('/assets/css/app.min.css') }}">
    <meta name="theme-color" content="#168C6A">
    <meta name="msapplication-TileColor" content="#168C6A">
    <meta name="apple-mobile-web-app-status-bar-style" content="#168C6A">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <script>
        if (screen.width > 699) {
            document.location.href = "/devices";
        }
        var _auth = <?php echo json_encode(Auth::check() ? Auth::user()->api_token : '')?>;
        var _colours = ['#8e44ad','#f1c40f','#0088cc','#16a085','#f122d6','#ff8800','#34495e','#e74c3c','#ff0000','#6772e5'];
    </script>
    @yield('head')
</head>
<body>

<div id="app">
    @yield('body')
</div>
<script src="https://unpkg.com/@ionic/core@0.0.2-30/dist/ionic.js"></script>
<script src="{{ mix('/assets/js/app.min.js') }}"></script>
<script>
$(document).ready(function () {
    if(navigator.standalone == true) {
        console.log('app is standalone')
    }

    // document.body.requestFullscreen();
})

</script>
@yield('js')
</body>
</html>
