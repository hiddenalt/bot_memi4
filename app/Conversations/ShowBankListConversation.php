<?php

namespace App\Conversations;

use App\Bank;
use App\Bot\Message\Button\Custom\BackButton;
use App\Bot\Message\Button\Custom\NextPageButton;
use App\Bot\Message\Button\Custom\PreviousPageButton;
use App\Bot\Message\Button\Primitive\DefaultButton;
use App\Bot\Message\Button\Primitive\PositiveButton;
use App\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class ShowBankListConversation extends BackFunctionConversation
{

    // Banks per page
    public int $perPage = 5;
    // Whitelist of conversations to show
    public array $conversationsIDs = [];
    // Whether to show all conversations in database or not (admin mode)
    public bool $showAll = false;
    // Whether to show all public conversations or not
    public bool $showPublic = true;


    /**
     * @return bool
     */
    public function isShowAll(): bool {
        return $this->showAll;
    }

    /**
     * @param bool $showAll
     */
    public function setShowAll(bool $showAll): void {
        $this->showAll = $showAll;
    }

    /**
     * @return int
     */
    public function getPerPage(): int {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void {
        $this->perPage = $perPage;
    }

    /**
     * @return array
     */
    public function getConversationsIDs(): array {
        return $this->conversationsIDs;
    }

    /**
     * @param array $conversationsIDs
     */
    public function setConversationsIDs(array $conversationsIDs): void {
        $this->conversationsIDs = $conversationsIDs;
    }

    /**
     * @return bool
     */
    public function isShowPublic(): bool {
        return $this->showPublic;
    }

    /**
     * @param bool $showPublic
     */
    public function setShowPublic(bool $showPublic): void {
        $this->showPublic = $showPublic;
    }




    /**
     * ShowBankListConversation constructor.
     * @param $previousConversation
     * @param array $conversationsID
     */
    public function __construct($previousConversation, array $conversationsID = []) {
        parent::__construct($previousConversation);
        $this->conversationsIDs = $conversationsID;
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
        $localConversation = Conversation::ofID($this->bot->getMessage()->getConversationIdentifier())->first();

        if(count($this->conversationsIDs) <= 0) $this->conversationsIDs[] = $localConversation->id;

        if(!$this->showAll){
            if($this->showPublic){
                $query->where("is_private" , 0);
                $query->orWhereIn("conversation_id", $this->conversationsIDs);
            } else {
                $query->whereIn("conversation_id", $this->conversationsIDs);
            }
        }

        $paginator = $query->paginate($this->perPage, ["*"], 'page', $page);
        $question = Question::create(__('bank-list.hint'));

        if($paginator->total() == 0){
            $question = Question::create(__('bank-list.empty-hint'));
        } else {
            foreach($paginator->items() as $item)
                $question->addButton(PositiveButton::create($item["title"])->value('bank-' . $item["id"]));

            if($page < $paginator->lastPage())  $question->addButton(new NextPageButton());
            if($page > 1)                       $question->addButton(new PreviousPageButton());
        }
        $question->addButton(DefaultButton::create(__("bank-list.create-new-bank"))->value("create_bank"));
        $question->addButton(new BackButton());

        return $this->ask($question, function (Answer $answer) use($page, $paginator, $localConversation) {

            if($answer->isInteractiveMessageReply()){
                switch($answer->getValue()){
                    case BackButton::BACK_VALUE:
                        return $this->moveBack();
                        break;
                    case "create_bank":
                        $this->bot->startConversation(
                            new BankCreateConversation($this,
                                $localConversation->id
                            )
                        );
                        return;
                        break;
                }
            }

            // List is empty
            if($paginator->total() == 0)
                return $this->showPage();

            // List has items
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();

                switch($selectedValue){
                    case PreviousPageButton::PREVIOUS_PAGE_VALUE:
                        return $this->showPage($page - 1);
                        break;

                    case NextPageButton::NEXT_PAGE_VALUE:
                        return $this->showPage($page + 1);
                        break;
                }

                // Bank clicked
                preg_match('/bank-([0-9+])/', $selectedValue, $matches);
                if(isset($matches[1])){
                    $id = $matches[1];
                    $this->chosen(Bank::ofID($id)->first());
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
                $this->chosen($result);
            }
        });
    }

    public function chosen(Bank $result){
        $id = $result->id;
        $this->say(__("bank-list.selected-conversation", [
            "title" => $result->title,
            "id" => $id
        ]));
        $this->bot->startConversation(new BankManagingConversation($this, $id));
    }

}
