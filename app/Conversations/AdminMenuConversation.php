<?php

namespace App\Conversations;

use App\Bot\Message\Button\Custom\BackButton;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class AdminMenuConversation extends BackFunctionConversation
{

    /**
     * Start the conversation.
     */
    public function run()
    {
        $this->showAdminMenu();
    }

    public function showAdminMenu(){
        //TODO: attachments (all types supported), voice mail, buttons

        $question = Question::create(__('admin-menu.ask-option-type'))
            ->addButtons([
                Button::create(__('admin-menu.manage-banks'))->value('banks')->additionalParameters([
                    "color" => "primary"
                ]),
                new BackButton()
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();

                switch($selectedValue){

                    case "banks":
                        $this->startBanksManagementConversation();
                        break;

                    case "back":
                        $this->moveBack();
                        break;

                    default:
                        $this->showAdminMenu();
                        break;
                }
            } else {
                $this->showAdminMenu();
            }
        });
    }

    public function startBanksManagementConversation(): void{
        $conversation = new ShowBankListConversation($this, []);
        $conversation->setShowAll(true);
        $this->bot->startConversation($conversation);
    }
}
