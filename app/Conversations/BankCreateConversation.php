<?php

namespace App\Conversations;

use App\Bank;
use App\Bot\Message\Button\Custom\NoButton;
use App\Bot\Message\Button\Custom\YesButton;
use App\Conversations\Type\FormConversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Str;

class BankCreateConversation extends FormConversation
{

    public int $ownerId = 1;
    public string $title = "";
    public string $description = "";
    public bool $isPrivate = true;


    public function __construct($previousConversation, int $ownerId) {
        parent::__construct($previousConversation);
        $this->ownerId = $ownerId;
    }

    public function run() {
        $this->askTitle();
    }

    public function askTitle() {
        $this->ask(__("create-bank.enter-title"), function (Answer $answer) {
            $this->title = $answer->getText();
            return $this->askDescription();
        });
    }

    public function askDescription() {
        $this->ask(__("create-bank.enter-description"), function (Answer $answer) {
            $this->description = $answer->getText();
            return $this->askIfPrivate();
        });
    }

    public function askIfPrivate() {

        $question = Question::create(__("create-bank.ask-if-private"));
        $question->addButton(new YesButton());
        $question->addButton(new NoButton());

        $this->ask($question, function (Answer $answer) {
            $value = ($answer->isInteractiveMessageReply()) ? $answer->getValue() : Str::lower($answer->getText());

            switch($value){
                case YesButton::YES_VALUE:
                case Str::lower(__("choice.yes")):
                    $this->isPrivate = true;
                    break;
                case NoButton::NO_VALUE:
                case Str::lower(__("choice.no")):
                    $this->isPrivate = false;
                    break;
                default:
                    $this->askIfPrivate();
                    return;
                    break;
            }

            return $this->createBank();
        });
    }

    public function createBank(){
        $bank = new Bank();
        $bank->conversation_id = $this->ownerId;
        $bank->title = $this->title;
        $bank->description = $this->description;


        $v = $bank->validator();
        if($v->fails()){
            $this->sayValidationDetails($v);
            return $this->askTitle();
        }

        $bank->save();
        $this->bot->startConversation(new BankManagingConversation($this->previousConversation, $bank->id));
    }


}
