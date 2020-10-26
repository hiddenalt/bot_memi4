<?php

namespace App\Conversations;

class BankChainListConversation extends BackFunctionConversation
{
    // TODO: BankChainListConversation!

    private int $bankId = 0;

    /**
     * BankChainListConversation constructor.
     * @param $conversation
     * @param int $bankId
     */
    public function __construct($conversation, int $bankId) {
        parent::__construct($conversation);
        $this->bankId = $bankId;
    }


    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        //
    }
}
