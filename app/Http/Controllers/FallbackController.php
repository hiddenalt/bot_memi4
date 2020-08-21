<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Support\Collection;

class FallbackController extends Controller
{
    /**
     * Respond with a generic message.
     *
     * @param Botman $bot
     * @return void
     */
    public function index(Botman $bot){
        $phrase = __("unknown-command.phrase-type-menu");

        $reply = (new Collection([
            __("unknown-command.phrase-1"),
            __("unknown-command.phrase-2"),
            __("unknown-command.phrase-3"),
            __("unknown-command.phrase-4"),
            __("unknown-command.phrase-5"),
            __("unknown-command.phrase-6"),
            __("unknown-command.phrase-7")
        ]))->random();

        $bot->reply($reply . " " . $phrase);
    }
}
