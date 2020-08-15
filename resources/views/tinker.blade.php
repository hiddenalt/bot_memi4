<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>BotMan Studio</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">

        <link href="/css/default.css" rel="stylesheet">

        <!-- Styles -->
        <style>

            .container {
                display: flex;
                height: 100vh;
                align-items: center;
                justify-content: center;
            }

            .content {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content" id="app">
                <botman-tinker api-endpoint="/api"></botman-tinker>
            </div>
        </div>

        <script src="/js/app.js"></script>
    </body>
</html>