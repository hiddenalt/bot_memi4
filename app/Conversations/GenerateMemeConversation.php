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
 * TODO: translate conversation to English
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

        $question = Question::create("Выбери тип мема, который надо сгенерировать:")
//            ->fallback('test_error')
//            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Мем "Когда"')->value('meme_when')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create('Мем-постирония')->value('meme_postirony')->additionalParameters([
                    "color" => "primary"
                ]),

                Button::create('Мем-демотиватор')->value('meme_demotivation')->additionalParameters([
                    "color" => "primary"
                ]),

                Button::create('Назад')->value('back')->additionalParameters([
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
                        $this->bot->reply("Таких мемов я пока что не делаю, выбери другой тип.");
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
