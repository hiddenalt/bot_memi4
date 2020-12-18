<?php

namespace App\Conversations;

use App\Bot\Message\Button\Custom\BackButton;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

/**
 * Class CreateMemeConversation
 * @package App\Conversations
 *
 */
class CreateMemeMenuConversation extends BackFunctionConversation
{
    /**
     * Start the conversation.
     *
     * @return void
     */
    public function run(): void{
        $this->askType();
    }

    public function askType(): CreateMemeMenuConversation {
        //TODO: attachments (all types supported), voice mail, buttons

        $question = Question::create(__('create-meme-conversation.ask-meme-type'))
            ->addButtons([
                Button::create(__('when-meme.title'))->value('when-meme')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create(__('4-frame-comics-meme.title'))->value('4-frame-comics-meme')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create(__('demotivational-poster-meme.title'))->value('demotivational-poster-meme')->additionalParameters([
                    "color" => "primary"
                ]),


                new BackButton()
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();

                switch($selectedValue){

                    case "back":
                        $this->moveBack();
                        break;

                    case "when-meme":
                        $this->askWhenMeme();
                        break;

                    case "4-frame-comics-meme":
                        $this->askBlocksMeme();
                        break;

                    case "demotivational-poster-meme":
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


    public function askWhenMeme(): void{
        $this->bot->startConversation(new CreateWhenMemeConversation());
    }
    public function askDemotivationMeme(): void{
        $this->bot->startConversation(new CreateDemotivationalPosterMemeConversation());
    }
    public function askBlocksMeme(): void{
        $this->bot->startConversation(new CreateSimpleComicsMemeConversation());
    }
}
