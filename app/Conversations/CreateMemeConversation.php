<?php

namespace App\Conversations;

use App\Bot\PictureGenerator\DemotivationMeme;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Log;

/**
 * Class CreateMemeConversation
 * @package App\Conversations
 *
 * TODO: translate conversation to English
 */
class CreateMemeConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askType();
    }

    public function askType(){
        //TODO: attachments (all types supported), voice mail, buttons

        $question = Question::create("Выбери тип мема, который надо создать:")
//            ->fallback('test_error')
//            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Мем "Когда"')->value('meme_when')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create('Блочный мем')->value('meme_blocks')->additionalParameters([
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

//                $this->bot->reply();
//                Log::info($selectedValue);

                switch($selectedValue){

                    case "meme_when":
                        $this->askWhenMeme();
                        break;

                    case "meme_blocks":
                        $this->askBlocksMeme();
                        break;

                    case "meme_demotivation":
                        $this->askDemotivationMeme();
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


    public function askWhenMeme(){
        $this->bot->startConversation(new CreateWhenMemeConversation());
    }
    public function askDemotivationMeme(){
        $this->bot->startConversation(new CreateDemotivationMemeConversation());
    }
    public function askBlocksMeme(){
        $this->bot->startConversation(new CreateBlocksMemeConversation());
    }
}
