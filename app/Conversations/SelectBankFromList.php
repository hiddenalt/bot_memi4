<?php

namespace App\Conversations;

use App\Bank;
use App\Conversations\Type\GenerateMemeConversation;

class SelectBankFromList extends ShowBankListConversation
{
    public function chosen(Bank $result) {
        /** @var GenerateMemeConversation $parent */
        $parent = &$this->previousConversation;
        $parent->addBank($result);
        $this->moveBack();
    }

}
