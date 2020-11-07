<?php

namespace App\Conversations\Type;

use App\Bank;
use App\Bot\Conversation\ConversationProxy;
use App\Conversations\BackFunctionConversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BankConversation extends BackFunctionConversation
{
    use ConversationProxy;

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

    abstract public function showMenu();

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

    /**
     * @param string|Question $question
     * @param array|Closure $next
     * @param array $additionalParameters
     * @return mixed
     */
    public function askOrSayBankError($question, $next, $additionalParameters = []){
        return $this->ask($question, function (Answer $answer) use($next) {
            $this->tryOrSayBankError(function () use ($answer, $next) {
                return (Closure::bind($next, $this))($answer);
            });
        }, $additionalParameters);
    }
}