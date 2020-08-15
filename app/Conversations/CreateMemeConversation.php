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

        $question = Question::create(__('create-meme-conversation.ask-meme-type'))
//            ->fallback('test_error')
//            ->callbackId('ask_reason')
            ->addButtons([
                Button::create(__('when-meme.title'))->value('meme_when')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create(__('4-block-comics-meme.title'))->value('meme_blocks')->additionalParameters([
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
                        $this->bot->reply(__('create-meme-conversation.unsupported-meme'));
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
