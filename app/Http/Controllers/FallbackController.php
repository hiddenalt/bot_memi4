<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FallbackController extends Controller
{
    /**
     * Respond with a generic message.
     *
     * @param Botman $bot
     * @return void
     */
    public function index(Botman $bot)
    {
        // TODO: translate to English
        $phrase = "Напиши 'меню' для вывода главного меню.";

        $bot->randomReply([
            "Извини, но я тебя не понял =(" . " ". $phrase,
            "???" . " ". $phrase,
            "Такую команду я не нашел... =(" . " ". $phrase,
            "Прости, до меня не дошло, не понимаю твою команду." . " ". $phrase,
            "Не понял =(" . " ". $phrase,
            "Эм... ну... я даже не знаю, что тебе ответить." . " ". $phrase,
            "Ну... хорошо, но я тебя всё-равно не понял." . " ". $phrase
        ]);
    }
}
