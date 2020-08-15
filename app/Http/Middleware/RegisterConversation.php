<?php

namespace App\Http\Middleware;

use App\Conversation;
use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use React\Dns\Query\Query;

class RegisterConversation implements Received, Captured, Matching, Heard, Sending
{
    /**
     * Handle a captured message.
     *
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     */
    public function captured(IncomingMessage $message, $next, BotMan $bot)
    {
        return $next($message);
    }

    /**
     * Handle an incoming message.
     *
     * @param IncomingMessage $message
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     * @throws BotManException
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $conversation_id = $message->getConversationIdentifier();
        $recipient = $message->getRecipient();

//        \App\Conversation::all()->where()

        // TODO: find the better way of implementation

        $exists = DB::table("conversations")->where([
            ["conversation_id", $conversation_id],
            ["local_id", $recipient]
        ])->exists();

        if(!$exists){

            DB::table("conversations")->insert([
                [
                    "conversation_id" => $conversation_id,
                    "local_id" => $recipient,
                    "created_at" => now(),
                    "updated_at" => now()
                ]
            ]);

            // TODO: translate to English
            $bot->say("Хе-хей, привет! Рад знакомству.", $recipient);
        }

        return $next($message);
    }

    /**
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
     * @param string $pattern
     * @param bool $regexMatched Indicator if the regular expression was matched too
     * @return bool
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched)
    {
        return true;
    }

    /**
     * Handle a message that was successfully heard, but not processed yet.
     *
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     */
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        return $next($message);
    }

    /**
     * Handle an outgoing message payload before/after it
     * hits the message service.
     *
     * @param mixed $payload
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     */
    public function sending($payload, $next, BotMan $bot)
    {
        return $next($payload);
    }
}
