<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;

class CreateNewBankConversation extends Conversation
{

    public string $title = "";
    public string $description = "";
    public bool   $isPrivate = true;
    public int    $conversationId = 0;

    /**
     * CreateNewBankConversation constructor.
     * @param int $conversationId
     */
    public function __construct(int $conversationId) {
        $this->conversationId = $conversationId;
    }

    // TODO: 1) ask for title, description, is private
    // TODO: 2) create a bank
    // TODO: 3) goto managing the bank

    /**
     * Start the conversation.
     */
    public function run()
    {

    }
}
