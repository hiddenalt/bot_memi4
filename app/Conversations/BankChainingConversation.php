<?php

namespace App\Conversations;

use App\Bot\Bank\Text\Chain\ChainManager;
use App\Bot\Text\Chain\DraftChain;
use App\Chain;
use App\Conversations\Type\BankConversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;

class BankChainingConversation extends BankConversation
{
    const MAX_RANDOM_PAIRS_PER_REQUEST = 10;

    /**
     * @return BankChainingConversation
     * @throws Exception
     */
    public function showMenu(){
        $question = Question::create(__('chaining-bank.hint'))
            ->addButtons([
                Button::create(__('chaining-bank.random-menu'))->value('random-menu'),
                Button::create(__('chaining-bank.learn-from-text'))->value('learn-from-text'),
                Button::create(__('chaining-bank.learn-the-pair'))->value('learn-the-pair'),
                Button::create(__('chaining-bank.edit-chains'))->value('edit-chains'),
                Button::create(__('menu.back'))->value('back')
            ]);

        return $this->askOrSayBankError($question, function (Answer $answer) {
            $this->respondMenu($answer);
        });
    }

    /**
     * @param Answer $answer
     * @return BankChainingConversation|void
     * @throws Exception
     */
    public function respondMenu(Answer $answer){

        if(!$answer->isInteractiveMessageReply())
            return $this->showMenu();

        switch($answer->getValue()){
            case "random-menu":
                $this->showRandomMenu();
                break;
            case "learn-from-text":
                $this->askTextToLearn();
                break;
            case "learn-the-pair":
                $this->askTargetWordToLearn();
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






    public string $target = "";
    public string $next = "";

    /**
     * Ask the first word in the pair to learn
     * @return BankChainingConversation
     */
    public function askTargetWordToLearn(){
        $question = Question::create(__('chaining-bank.learn-the-pair.type-target-word'))
            ->addButtons([
                Button::create(__('menu.back'))->value('back')
            ]);

        return $this->ask($question, function(Answer $answer){
            if($answer->isInteractiveMessageReply() and $answer->getValue() == "back")
                return $this->showMenu();

            // Dot = new sentence
            $this->target = ($answer->getText() == ".") ? "" : $answer->getText();


            $this->askNextWordToLearn();
        });
    }

    /**
     * Ask the second word in the pair to learn
     * @return BankChainingConversation
     */
    public function askNextWordToLearn(){
        $question = Question::create(__('chaining-bank.learn-the-pair.type-next-word'))
            ->addButtons([
                Button::create(__('menu.back'))->value('back')
            ]);

        return $this->askOrSayBankError($question, function(Answer $answer){
            if($answer->isInteractiveMessageReply() and $answer->getValue() == "back")
                return $this->showMenu();

            $this->next = $answer->getText();
            return $this->performLearningThePair();
        });
    }

    /**
     * @throws Exception
     */
    public function performLearningThePair(){
        $chainManager = new ChainManager([$this->getBank()]);
        $chainManager->learn($this->target, $this->next);

        $this->say(__('chaining-bank.learn-the-pair.success'));

        // Loop asking the questions
        return $this->askTargetWordToLearn();
    }





    /**
     * @return BankConversation
     */
    public function askTextToLearn(){
        $question = Question::create(__("chaining-bank.learn-the-text.send-the-text"))
            ->addButtons([
                Button::create(__('menu.back'))->value('back')
            ]);

        return $this->askOrSayBankError($question, function(Answer $answer){
            $this->performTextToLearn($answer);
        });
    }

    /**
     * @param Answer $answer
     * @return BankConversation
     * @throws Exception
     */
    public function performTextToLearn(Answer $answer){
        if($answer->isInteractiveMessageReply() and $answer->getValue() == "back")
            return $this->showMenu();

        $chainManager = new ChainManager([$this->getBank()]);
        $chainManager->learnText($answer->getText());

        $this->say(__('chaining-bank.learn-the-text.success'));

        // Loop asking the questions
        return $this->askTextToLearn();
    }




    /**
     * Show random pairing menu
     */
    public function showRandomMenu(){

        $question = Question::create(__('chaining-bank.random-menu.hint'))
            ->addButtons([
                Button::create(__('chaining-bank.random-menu.do-once'))->value('1'),
                Button::create(__('chaining-bank.random-menu.do-3-times'))->value('3'),
                Button::create(__('chaining-bank.random-menu.do-5-times'))->value('5'),
                Button::create(__('chaining-bank.random-menu.do-10-times'))->value('10'),
                Button::create(__('menu.back'))->value('back')
            ]);

        return $this->askOrSayBankError($question, function (Answer $answer) {
            $this->performRandomMenu($answer);
        });

    }

    /**
     * Perform the user's response
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

        return $this;
    }

    /**
     * Create random pairing
     * @param Answer $answer
     * @return BankChainingConversation
     * @throws Exception
     */
    public function performRandom(Answer $answer){

        // Checkup for existence
        $this->getBank();

        $times =
            ((int)$answer->getValue() > self::MAX_RANDOM_PAIRS_PER_REQUEST) ?
                self::MAX_RANDOM_PAIRS_PER_REQUEST : (int)$answer->getValue();

        $chainManager = new ChainManager([$this->bankId]);

        $response = [];

        // TODO: prettify the response
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

            $target = $chain->target()->first();
            $next = $chain->next()->first();

            $targetText = ($target->text == "") ? __("new-sentence") : $target->text;
            $nextText = ($next->text == "") ? __("end-of-sentence") : $next->text;

            $response[] = "#". ($i + 1) . ": " . $targetText . " " . $nextText;
        }

        $this->bot->reply(__("chaining-bank.random-menu.random-result", [
            "list" => implode("\n", $response)
        ]));
        return $this->showRandomMenu();
    }
}
