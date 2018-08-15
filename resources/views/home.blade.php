<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Call Recording Transcriber</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ secure_asset('css/app.css?p='.time()) }}" rel="stylesheet">

    <!-- Scripts -->

    <script src="{{ secure_asset('js/app.js?p='.time()) }}" defer></script>
    <script src="//cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.js"></script>

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'recordings' => $recordings
        ]); ?>;
    </script>
</head>
<body>
    <div id="app">
        <div class="flex-center position-ref full-height">
            <div class="content">
                <recording-page></recording-page>
            </div>
        </div>
    </div>
</body>
</html>
