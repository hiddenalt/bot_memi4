<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Memer the Bot v.{{ env("version") }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Lato&family=Montserrat&family=Open+Sans&family=Roboto&family=Roboto+Condensed&display=swap" rel="stylesheet">

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

            .logo {
                width: 256px;
                height: 256px;
            }

            .links{
                margin-top: 10px;
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }

            .links a {
                font-size: 1.25rem;
                text-decoration: none;
                margin: 10px;
                width: 50px;
            }

            @media all and (max-width: 500px) {
                .links {
                    display: flex;
                    flex-direction: column;
                    width: auto;
                }
                .logo{
                    width: 100%;
                    height: unset;
                }
            }

            h3{
                margin-bottom: 0px;
            }

        </style>
    </head>
    <body>
    <!-- TODO: restyle welcome page -->
        <div class="container">
            <div class="content">
                <div class="logo-container">
                    <img src="/logo.png" class="logo" alt="Логотип">
                    <h3>Memer the Bot v.{{ env("version") }}</h3>
                    <label>Powered by PHP 7.4, BotMan (Laravel)</label>
                </div>

{{--                <div class="links">--}}
{{--                    <a href="/chat">Chat</a>--}}
{{--                    <span class="flex-separator"></span>--}}
{{--                    <a href="https://vk.com/bot_memi4">VK</a>--}}
{{--                </div>--}}
            </div>
        </div>
    </body>
</html>