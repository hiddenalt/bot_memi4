<?php

namespace App\Conversations;

use App\Bot\Message\Button\Custom\BackButton;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

/**
 * Class GenerateMemeConversation
 * @package App\Conversations
 */
class GenerateMemeMenuConversation extends BackFunctionConversation
{

    /**
     * Start the conversation
     */
    public function run(): void{
        $this->askType();
    }

    /**
     * First question
     */
    public function askType(){

        //TODO: attachments (all types supported), voice mail, buttons

        $question = Question::create(__("generate-meme-conversation.ask-meme-type"))
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
//                $selectedText = $answer->getText();

                switch($selectedValue){

                    case "back":
                        $this->moveBack();
                        break;

                    case "when-meme":
                        $this->bot->startConversation(new GenerateWhenMemeConversation($this));
                        break;

                    case "demotivational-poster-meme":
                        $this->bot->startConversation(new GenerateDemotivationalPosterMemeConversation($this));
                        break;

                    case "4-frame-comics-meme":
                        $this->bot->startConversation(new GenerateSimpleComicsMemeConversation($this));
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

}
