<?php

namespace App\Conversations;

use App\Bank;
use App\Bot\Message\Button\Custom\BackButton;
use App\Bot\Message\Button\Custom\NextPageButton;
use App\Bot\Message\Button\Custom\PreviousPageButton;
use App\Bot\Message\Button\Primitive\PositiveButton;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class ShowBankListConversation extends BackFunctionConversation
{

    // -1 = all banks (for admin)
    public int $conversationID = -1;
    // Banks per page
    public int $perPage = 5;

    /**
     * ShowBankListConversation constructor.
     * @param $previousConversation
     * @param int $conversationID
     */
    public function __construct($previousConversation, int $conversationID) {
        parent::__construct($previousConversation);
        $this->conversationID = $conversationID;
    }

    /**
     * Start the conversation.
     */
    public function run()
    {
        $this->showPage();
    }

    /**
     * Show banks list per page
     * @param int $page
     * @return ShowBankListConversation|void
     */
    public function showPage(int $page = 1){
        $query = Bank::query();

        if($this->conversationID > -1)
            $query->where("conversation_id" , $this->conversationID);

        $paginator = $query->paginate($this->perPage, ["*"], 'page', $page);

        if($paginator->total() == 0){
            $this->say(__("bank-list.empty-hint"));
            $this->bot->startConversation(new AdminMenuConversation(null));
            return;
        }

        $buttons = [];
        foreach($paginator->items() as $item)
            $buttons[] = PositiveButton::create($item["title"])->value('bank-' . $item["id"]);

        if($page < $paginator->lastPage()) $buttons[] = new NextPageButton();
        if($page > 1) $buttons[] = new PreviousPageButton();
        $buttons[] =  new BackButton();

        $question = Question::create(__('bank-list.hint'))
            ->addButtons($buttons);

        return $this->ask($question, function (Answer $answer) use($page) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();

                switch($selectedValue){
                    case PreviousPageButton::PREVIOUS_PAGE_VALUE:
                        return $this->showPage($page - 1);
                        break;

                    case NextPageButton::NEXT_PAGE_VALUE:
                        return $this->showPage($page + 1);
                        break;

                    case BackButton::BACK_VALUE:
                        return $this->moveBack();
                        break;
                }

                // Bank clicked
                preg_match('/bank-([0-9+])/', $selectedValue, $matches);
                if(isset($matches[1])){
                    $id = $matches[1];
                    $this->bot->startConversation(new BankManagingConversation($this, $id));
                    return;
                }

                // Generic
                $this->showPage();
            } else {
                $message = $answer->getText();

                // Search the matched
                $searchQuery = Bank::query();
                // TODO: sql escape?
                $result = $searchQuery
                    ->where("id", "=", $message)
                    ->orWhere("title", "LIKE", "%" . $message . "%")
                    ->orWhere("description", "LIKE", "%" . $message . "%")
                    ->first();

                // Not found
                if($result == null){
                    $this->say(__("bank-list.not-found"));
                    $this->showPage();
                    return;
                }

                // If found
                $id = $result->id;
                $this->say(__("bank-list.found-conversation", [
                    "title" => $result->title,
                    "id" => $id
                ]));
                $this->bot->startConversation(new BankManagingConversation($this, $id));
            }
        });
    }
}
