<?php

namespace App\Conversations;

use App\Bank;
use App\Bot\Conversation\ConversationProxy;
use App\Bot\Text\Chain\DraftChain;
use App\Bot\Text\ChainManager;
use App\Chain;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BankChainingConversation extends BackFunctionConversation
{
    use ConversationProxy;

    const MAX_RANDOM_PAIRS_PER_REQUEST = 10;

    public int $bankId = 0;

    /**
     * BankChainingConversation constructor.
     * @param $previousConversation
     * @param int $bankId
     */
    public function __construct($previousConversation, int $bankId) {
        parent::__construct($previousConversation);
        $this->bankId = $bankId;
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

    /**
     * If got, respond the exception
     * @param callable $try
     */
    public function tryOrSayBankError(callable $try){
        $this->tryOrSayErrorAndMoveBack($try, __("manage-bank.error-exception"));
    }

    /**
     * @return BankChainingConversation
     * @throws Exception
     */
    public function showMenu(){
//        $bank = $this->getBank();



        $question = Question::create(__('chaining-bank.hint'))
            ->addButtons([
                Button::create(__('chaining-bank.random-menu'))->value('random-menu'),
                Button::create(__('chaining-bank.learn-from-text'))->value('learn-from-text'),
                Button::create(__('chaining-bank.learn-the-pair'))->value('learn-the-pair'),
                Button::create(__('chaining-bank.edit-chains'))->value('edit-chains'),
                Button::create(__('menu.back'))->value('back')
            ]);

        return $this->ask($question, function (Answer $answer) {
            $this->tryOrSayBankError(function() use($answer){
                $this->respondMenu($answer);
            });
        });
    }


    //TODO: menu "Random", "Learn from text", "Learn the pair" (ask separately target & next)

    /**
     * @param Answer $answer
     * @return BankChainingConversation
     * @throws Exception
     */
    public function respondMenu(Answer $answer){

        if(!$answer->isInteractiveMessageReply()){
            return $this->showMenu();
        }

        switch($answer->getValue()){
            case "random-menu":
                $this->showRandomMenu();
                break;
            case "learn-from-text":

                break;
            case "learn-the-pair":

                break;
            case "edit-chains":
                $this->bot->startConversation(new BankChainListConversation($this, $this->bankId));
                break;

            case "back":
                $this->moveBack();
                break;

            default:
                $this->showMenu();
                break;
        }
    }

    public function showRandomMenu(){

        $question = Question::create(__('chaining-bank.random-menu.hint'))
            ->addButtons([
                Button::create(__('chaining-bank.random-menu.do-once'))->value('1'),
                Button::create(__('chaining-bank.random-menu.do-3-times'))->value('3'),
                Button::create(__('chaining-bank.random-menu.do-5-times'))->value('5'),
                Button::create(__('chaining-bank.random-menu.do-10-times'))->value('10'),
                Button::create(__('menu.back'))->value('back')
            ]);

        return $this->ask($question, function (Answer $answer) {
            $this->tryOrSayBankError(function() use($answer){
                $this->performRandomMenu($answer);
            });
        });

    }

    /**
     * @param Answer $answer
     * @return BankChainingConversation
     * @throws Exception
     */
    public function performRandomMenu(Answer $answer){
        if(!$answer->isInteractiveMessageReply()){
            return $this->showRandomMenu();
        }

        switch($answer->getValue()){
            case "back":
                $this->showMenu();
                break;

            default:
                if((int)$answer->getValue() > 0)
                    $this->performRandom($answer);
                else
                    $this->showRandomMenu();

                break;
        }

    }

    /**
     * @param Answer $answer
     * @return BankChainingConversation
     * @throws Exception
     */
    public function performRandom(Answer $answer){


        $times =
            ((int)$answer->getValue() > self::MAX_RANDOM_PAIRS_PER_REQUEST) ?
                self::MAX_RANDOM_PAIRS_PER_REQUEST : (int)$answer->getValue();

        $chainManager = new ChainManager([$this->bankId]);

        $response = [];

        for($i = 0; $i < $times; $i++){
            /** @var DraftChain|null $pair */
            $pair = $chainManager->pickRandomUnregisteredPair();
            if($pair == null) {
                $response[] = "-";
                break;
            }


            $chain = Chain::query()->firstOrCreate([
                "bank_id" => $this->bankId,
                "target" => $pair->target,
                "next" => $pair->next
            ]);
            $response[] = "#". ($i + 1) . ": " . $chain->target()->first()->text . " " . $chain->next()->first()->text;
        }

        $this->bot->reply(__("chaining-bank.random-menu.random-result", [
            "list" => implode("\n", $response)
        ]));
        return $this->showRandomMenu();
    }

    /**
     * Retrieves the bank instance (throws error if null)
     * @return Builder|Model|object|null
     * @throws Exception
     */
    public function getBank(){
        $bank = Bank::query()->where("id", $this->bankId)->first();

        if($bank == null || $bank->conversation() == null)
            throw new Exception(__("manage-bank.not-found-error"));

        return $bank;
    }



}
