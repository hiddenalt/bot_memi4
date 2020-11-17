<?php


namespace App\Conversations\Type;


use App\Bank;
use App\Bot\Conversation\ConversationProxy;
use App\Conversations\BackFunctionConversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

abstract class GenerateMemeConversation extends BackFunctionConversation {

    use ConversationProxy;

    abstract public function getStartKeyWord(): string;


    /** @var Bank[] $usedBanks */
    public array $usedBanks = [];

    public function run() {
        $this->usedBanks = [
            new Bank(["id" => 1])
        ];
        $this->showMenu();
    }

    public function showMenu(){
        $question = Question::create(__('generate-meme.hint'))
            ->addButtons([
                Button::create(__('generate-meme.execute'))->value('execute')->additionalParameters([
                    "color" => "primary"
                ]),
//                Button::create(__('generate-meme.edit-bank-list'))->value('edit-bank-list')->additionalParameters([
//                    "color" => "primary"
//                ]),
                Button::create(__('menu.back'))->value('back')->additionalParameters([
                    "color" => "secondary"
                ])
            ]);
        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();

                switch($selectedValue){

                    case "back":
                        $this->moveBack();
                        break;

                    case "execute":
                        $this->tryOrSayErrorAndMoveBack(function (){
                            $this->generateMeme();
                            $this->showMenu();
                        }, __("generate-meme-conversation.generating-error"));
                        break;

                    default:
                        $this->bot->reply(__('generate-meme-conversation.unknown-command'));
                        $this->showMenu();

                        break;
                }
            } else {
                $this->showMenu();
            }
        });
    }

    abstract public function generateMeme();

}