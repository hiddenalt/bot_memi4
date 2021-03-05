<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Memer the Bot</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.8.55/css/materialdesignicons.min.css" rel="stylesheet">


        <link href="{{ asset("/assets/static/style.css") }}" rel="stylesheet">
    </head>
    <body>
    <!-- TODO: restyle welcome page -->
        <div class="container">
            <div class="content">
                <div class="logo-container">
                    <img src="/logo.png" class="logo" alt="Logo">
                    <h2 class="title">Memer the Bot</h2>
                    <label class="subtitle">@version</label>
                    <label class="subtitle">Powered by PHP {{ phpversion() }}, BotMan (Laravel)</label>
                </div>

                <div class="links">
                    <a class="link" href="{{ asset("menu") }}"><span class="mdi mdi-36px mdi-application-export"></span></a>
                    <span class="flex-separator"></span>
                    <a class="link" href="{{ asset("graphql-playground") }}"><span class="mdi mdi-36px mdi-graphql"></span></a>
                    <span class="flex-separator"></span>
                    <a class="link" href="{{ env("WELCOME_PAGE_TELEGRAM_URL") }}"><span class="mdi mdi-36px mdi-telegram"></span></a>
                    <span class="flex-separator"></span>
                    <a class="link" href="{{ env("WELCOME_PAGE_VK_URL") }}"><span class="mdi mdi-36px mdi-vk"></span></a>
                    <span class="flex-separator"></span>
                    <a class="link" href="{{ env("WELCOME_PAGE_GITHUB_URL") }}"><span class="mdi mdi-36px mdi-github"></span></a>
                    <span class="flex-separator"></span>
                    <a class="link" href="{{ env("WELCOME_PAGE_WIKI") }}"><span class="mdi mdi-36px mdi-book-open-variant"></span></a>
                </div>
            </div>
        </div>
    </body>
</html>