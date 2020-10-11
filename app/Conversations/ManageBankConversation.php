<?php

namespace App\Conversations;

use App\Bank;
use App\Bot\Conversation\ConversationProxy;
use App\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ManageBankConversation extends BackFunctionConversation
{
    use ConversationProxy;

    // Max chars for bank's description
    const DESCRIPTION_MAX_CHARS = 300;


    public int $bankID = 0;

    /**
     * ManageBankConversation constructor.
     * @param $previousConversation
     * @param int $bankID
     */
    public function __construct($previousConversation, int $bankID) {
        parent::__construct($previousConversation);
        $this->bankID = $bankID;
    }

    /**
     * If got, respond the exception
     * @param callable $try
     */
    public function tryOrSayBankError(callable $try){
        $this->tryOrSayErrorAndMoveBack($try, __("manage-bank.error-exception"));
    }

    /**
     * Start the conversation.
     */
    public function run()
    {
        $this->tryOrSayBankError(function(){
            $this->showMenu();
        });
    }

    // TODO: buttons: "Edit config/properties", "Delete", "Teach a text", "Push an image"

    /**
     * Show main menu
     * @return ManageBankConversation
     * @throws Exception
     */
    public function showMenu(){
        $bank = $this->getBank();

        // Data
        $title = $bank->title;
        $description = $bank->description;
        /** @var Conversation $conversation */
        $conversation = $bank->conversation()->first();
        $conversation_id = $conversation->id;
        $conversation_title = $conversation->title;

        // Slice description
        $description = Str::limit($description, self::DESCRIPTION_MAX_CHARS);


        $question = Question::create(__('manage-bank.info', [
            "title" => $title,
            "description" => $description,
            "id" => $this->bankID,
            "created_at" => $bank->created_at,
            "updated_at" => $bank->updated_at,
            "conversation_id" => $conversation_id,
            "conversation_title" => ($conversation_title ?? "-")
        ]))
            ->addButtons([
                Button::create(__('manage-bank.see-full-description'))->value('see-full-description'),
                Button::create(__('manage-bank.edit'))->value('edit'),
                Button::create(__('manage-bank.delete'))->value('delete'),
                Button::create(__('manage-bank.learn-a-text'))->value('learn-a-text'),
                Button::create(__('manage-bank.push-an-image'))->value('push-an-image'),
                Button::create(__('manage-bank.refresh-info'))->value('refresh-info'),
                Button::create(__('menu.back'))->value('back')
            ]);

        return $this->ask($question, function (Answer $answer) use($bank) {
            $this->tryOrSayBankError(function() use($answer){
                $this->respondMenu($answer);
            });
        });
    }

    /**
     * @param Answer $answer
     * @throws Exception
     */
    public function respondMenu(Answer $answer){
        $bank = $this->getBank();

        if ($answer->isInteractiveMessageReply()) {
            $selectedValue = $answer->getValue();

            switch($selectedValue){

                case "learn-a-text":
                    $this->bot->startConversation(new BankChainingConversation($this, $this->bankID));
                    break;

                case "see-full-description":
                    $this->say($bank->description);
                    $this->showMenu();
                    break;

                case "refresh-info":
                    $this->showMenu();
                    break;

                case "edit":
                    $this->askEditTitle();
                    break;

                case "back":
                    $this->moveBack();
                    break;

            }
        } else {
//            $message = $answer->getText();
            $this->say(__("unknown-option-selected"));
            $this->showMenu();
        }
    }

    /**
     * Ask the new bank title
     * @return ManageBankConversation
     * @throws Exception
     */
    public function askEditTitle(){
        $bank = $this->getBank();

        $question = Question::create(__("manage-bank.enter-new-title", [
            "title" => $bank->title
        ]));

        return $this->ask($question, function (Answer $answer) use($bank) {
            $this->tryOrSayBankError(function() use($answer){
                $this->respondEditTitle($answer);
            });
        });
    }

    /**
     * @param Answer $answer
     * @throws Exception
     */
    public function respondEditTitle(Answer $answer){
        $bank = $this->getBank();

        $text = trim($answer->getMessage()->getText());

        if($text == ""){
            $this->askEditTitle();
        } else {
            $bank->title = $text;
            $bank->save();

            $this->askEditBankDescription();
        }
    }

    /**
     * Ask the new bank description
     * @return ManageBankConversation
     * @throws Exception
     */
    public function askEditBankDescription(){
        $bank = $this->getBank();

        $question = Question::create(__("manage-bank.enter-new-description", [
            "description" => $bank->description
        ]));

        return $this->ask($question, function (Answer $answer) use($bank) {
            $this->tryOrSayBankError(function() use($answer){
                $this->respondEditBankDescription($answer);
            });
        });
    }

    /**
     * @param Answer $answer
     * @throws Exception
     */
    public function respondEditBankDescription(Answer $answer){
        $bank = $this->getBank();

        $text = trim($answer->getMessage()->getText());
        if($text == ""){
            $this->askEditBankDescription();
        } else {
            $bank->description = $text;
            $bank->save();

            $this->say(__("manage-bank.rename-done"));
            $this->showMenu();
        }
    }

    /**
     * Retrieves the bank instance (throws error if null)
     * @return Builder|Model|object|null
     * @throws Exception
     */
    public function getBank(){
        $bank = Bank::query()->where("id", $this->bankID)->first();

        if($bank == null || $bank->conversation() == null)
            throw new Exception(__("manage-bank.not-found-error"));

        return $bank;
    }
}
