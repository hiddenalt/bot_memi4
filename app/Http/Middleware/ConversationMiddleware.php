<?php

namespace App\Http\Middleware;

use App\Conversation;
use App\Conversations\SelectLanguageConversation;
use App\Language;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Matcher;
use Illuminate\Support\Facades\DB;

class ConversationMiddleware implements Received, Captured, Matching, Heard, Sending
{
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
     * @throws BotManException
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        // TODO: optimize with models

        $conversation_id = $message->getConversationIdentifier();
        $recipient = $message->getRecipient();



        // Registering the conversation
        $exists = DB::table("conversations")->where([
            ["conversation_id", $conversation_id],
            ["local_id", $recipient]
        ])->exists();

        $language = new Language();

        if(!$exists){
            DB::table("conversations")->insert([
                [
                    "conversation_id" => $conversation_id,
                    "local_id" => $recipient,
                    "created_at" => now(),
                    "updated_at" => now()
                ]
            ]);

            // Set to default system language
            $language->setLocale(Language::DEFAULT_LOCALE);

            // Say hello and ask the preferred language
            $bot->say(__("greetings"), $recipient);
            $bot->startConversation(new SelectLanguageConversation(), $recipient, $bot->getDriver()->getName());
            return $next($message);
        }

        // Set current locale
        $conversation = Conversation::query()->where("conversation_id", $conversation_id)->first();
        if(!$language->setLocale($conversation->language ?? Language::DEFAULT_LOCALE))
            $bot->say(__("language.selection-error"), $recipient);

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
        return $regexMatched
            // Native-language patterns matcher
            // TODO: native-language matching optimization
            || (
                preg_match("/%%%{{{(.+)}}}%%%/i", $pattern, $matches) &&
                (new Matcher())->isPatternValid($message, new Answer(), __($matches[1]))
            );
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
