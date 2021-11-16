<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Cameroonians Association Online">
    <meta name="author" content="Eddy Ella">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="{{ mix('/assets/css/app.min.css') }}">
    <title>Incompatible device</title>
    <script>
        if (screen.width < 699) {
            document.location.href = "/";
        }
    </script>

    <style media="screen">
        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
            padding: 0 20px;
        }
        .title { font-size: 2.9em }
        .logo { width: 100%; font-size: 1.5em }
    </style>
</head>
<body>
    <div class="full-height flex-center position-ref">
        <div class="content">
            <div class="logo">
                Cameroonians Associations Online
            </div>
            <div class="title">
                This application only works on mobile phones.
            </div>
        </div>
    </div>
</body>
</html>
