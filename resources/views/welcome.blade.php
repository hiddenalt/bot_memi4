<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Memer the Bot v.{{ env("version") }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.8.55/css/materialdesignicons.min.css" rel="stylesheet">

        <style>
            html, body{
                padding: 0;
                margin: 0;
                /*font-family: 'Gabriela', serif;*/
                font-family: 'Roboto', sans-serif;
                /*overflow: hidden;*/
            }

            .container {
                display: flex;
                height: 100vh;
                align-items: center;
                justify-content: center;
                margin: auto;
            }

            .content {
                text-align: center;
                margin: auto;
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

            a.link {
                font-size: 1.25rem;
                text-decoration: none;
                margin: 10px;
                width: 36px;
                height: 36px;
                color: #22292f;

                display: flex;
                align-items: center;
                justify-content: center;
            }

            @media all and (max-width: 500px) {
                .links {
                    display: flex;
                    width: 100%;
                    flex-wrap: wrap;
                }
                .logo{
                    width: 100%;
                    height: unset;
                }
            }

            h2{
                margin-bottom: 0;
            }


            @keyframes move {
                from {
                    transform: translate(calc(100vw * -1), 50vh) rotate(0deg);
                }
                20% {
                    transform: translate(calc(50vw * -1), calc(50vh - 36px)) rotate(30deg);
                }

                60% {
                    transform: translate(calc(50vw * -1), calc(50vh - 36px)) rotate(30deg);
                }

                80% {
                    transform: translate(calc(30vw * -1), calc(-10vh)) rotate(10deg);
                }

                to {
                    transform: translate(0, 0) rotate(0deg);
                }
            }

            @keyframes turn_up {
                from {
                    transform: translate(0, -10px);
                    opacity: 0;
                }
                to {
                    transform: translate(0, 0);
                    opacity: 1;
                }
            }

            @keyframes scroll {
                from {
                    overflow: hidden;
                }
                to {
                    overflow: auto;
                }
            }

            .logo {
                animation: move 1.5s 1 ease-in-out;
            }

            .title {
                opacity: 0;
                animation: turn_up .3s 1 ease-in-out;
                animation-delay: 1.5s;
                animation-fill-mode: forwards;
            }

            .subtitle {
                opacity: 0;
                animation: turn_up .3s 1 ease-in-out;
                animation-delay: 1.8s;
                animation-fill-mode: forwards;
                display: block;
            }

            .flex-separator{
                background: black;
                width: 2px;
                height: 2px;
                border-radius: 2px;
            }

            .links{
                opacity: 0;
                animation: turn_up .3s 1 ease-in-out;
                animation-delay: 2.1s;
                animation-fill-mode: forwards;
            }

            .link{
                transition-duration: .1s;
            }

            .link:hover{
                transform: scale(1.2);
            }

            .link:active{
                color: #2779bd;
            }

            html, body{
                overflow: hidden;
                animation: scroll .1s 1 ease-in-out;
                animation-delay: 1.3s;
                animation-fill-mode: forwards;
            }

        </style>
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
                    <a class="link" href="{{ env("WELCOME_PAGE_TELEGRAM_URL") }}"><span class="mdi mdi-36px mdi-telegram"></span></a>
                    <span class="flex-separator"></span>
                    <a class="link" href="{{ env("WELCOME_PAGE_VK_URL") }}"><span class="mdi mdi-36px mdi-vk"></span></a>
                    <span class="flex-separator"></span>
                    <a class="link" href="https://github.com/hiddenalt/memer-the-bot"><span class="mdi mdi-36px mdi-github"></span></a>
                    <span class="flex-separator"></span>
                    <a class="link" href="https://github.com/hiddenalt/memer-the-bot/wiki"><span class="mdi mdi-36px mdi-book-open-variant"></span></a>
                </div>
            </div>
        </div>
    </body>
</html>