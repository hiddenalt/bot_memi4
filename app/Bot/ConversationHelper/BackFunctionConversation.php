<?php


namespace App\Conversations;


use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Conversations\Conversation;

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
        // Move back if found
        if($this->previousConversation instanceof Conversation) {
            $this->bot->startConversation($this->previousConversation);
            return true;
        }

        // Send main menu if no previous menu found
        (new BotManController())->sendMenu($this->bot);
        return false;
    }

    /**
     * Replaceable method.
     */
    public function run(){}
}