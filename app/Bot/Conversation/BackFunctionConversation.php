<?php


namespace App\Conversations;


use App\Bot\Message\Button\Custom\BackButton;
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use Closure;

class BackFunctionConversation extends Conversation {

    /** @var mixed null */
    public $previousConversation = null;

    /**
     * BackFunctionConversation constructor.
     * @param mixed $previousConversation
     */
    public function __construct($previousConversation) {
        $this->previousConversation = $previousConversation;
    }

    /**
     * Move to the previous conversation.
     * Returns true if succeed.
     * @return bool
     */
    public function moveBack(){

        $this->beforeGoBack();

        // Move back if found
        if($this->previousConversation instanceof Conversation) {
            $this->bot->startConversation($this->previousConversation);
            return true;
        }

        // Send main menu if no previous menu found
        (new BotManController())->sendMenu($this->bot);
        return false;
    }

    public function beforeGoBack(){}


    /**
     * Replaceable method.
     */
    public function run(){}

    public function ask($question, $next, $additionalParameters = []) {
        if(is_string($question)){
            return parent::ask(Question::create($question)->addButton(new BackButton()), function(Answer $answer) use($next){
                if($answer->isInteractiveMessageReply() && $answer->getValue() == BackButton::BACK_VALUE)
                    return $this->moveBack();

                return (Closure::bind($next, $this))($answer);
            }, $additionalParameters);
        }

        return parent::ask($question, $next, $additionalParameters);
    }


}