<?php

namespace App\Http\Middleware;

use App\Conversation;
use App\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class CheckUpForPermissionOrSkip implements Received, Captured, Matching, Heard, Sending
{
    private string $permission = "";

    /**
     * CheckUpForPermissionOrSkip constructor.
     * @param string $permission
     */
    public function __construct(string $permission) {
        $this->permission = $permission;
    }


    /**
     * Handle a captured message.
     *
     * @param IncomingMessage $message
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
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        return $next($message);
    }

    /**
     * @param IncomingMessage $message
     * @param string $pattern
     * @param bool $regexMatched Indicator if the regular expression was matched too
     * @return bool
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched)
    {
        /** @var User $user */
//        $user = System::getUserFromConversation($message->getConversationIdentifier());

        /** @var Conversation $conversation */
        $conversation = Conversation::all()->where("conversation_id", $message->getConversationIdentifier())->first();

        if($conversation == null)
            return false;

        /** @var User $user */
        $user = $conversation->user();

        if($user == null || (!$user->hasPermissionTo($this->permission) && !$user->isSuperAdmin()))
            return false;

        return $regexMatched;
    }

    /**
     * Handle a message that was successfully heard, but not processed yet.
     *
     * @param IncomingMessage $message
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
