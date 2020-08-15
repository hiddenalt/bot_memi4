<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

/**
 * Class GenerateMemeConversation
 * @package App\Conversations
 */
class GenerateMemeConversation extends Conversation
{

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askType();
    }

    /**
     * First question
     */
    public function askType()
    {

        //TODO: attachments (all types supported), voice mail, buttons

        $question = Question::create(__("generate-meme-conversation.ask-meme-type"))
//            ->fallback('test_error')
//            ->callbackId('ask_reason')
            ->addButtons([
                Button::create(__('when-meme.title'))->value('meme_when')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create(__('4-block-comics-meme.title'))->value('meme_postirony')->additionalParameters([
                    "color" => "primary"
                ]),

                Button::create(__('demotivation-meme.title'))->value('meme_demotivation')->additionalParameters([
                    "color" => "primary"
                ]),

                Button::create(__('menu.back'))->value('back')->additionalParameters([
                    "color" => "secondary"
                ])
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();
//                $selectedText = $answer->getText();

                switch($selectedValue){

                    case "meme_when":
                        $this->sendWhenMeme();
                        break;

                    default:
                        $this->bot->reply(__('generate-meme-conversation.unsupported-meme'));
                        $this->askType();

                        break;
                }
            } else {
                $this->askType();
            }
        });
    }


    public function sendWhenMeme(){

    }

}
