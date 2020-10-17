<?php

namespace App\Conversations;

use App\Bot\Conversation\ConversationProxy;
use App\Http\Controllers\BotManController;
use App\Language;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class SelectLanguageConversation extends Conversation
{
    use ConversationProxy;

    const PAGINATION_CHUNK_SIZE = 5;

    /**
     * Start the conversation.
     */
    public function run(){
        $this->askSelection();
    }

    /**
     * @param int $page
     * @return SelectLanguageConversation|void
     */
    public function askSelection(int $page = 0){
        $codesCollection = $this->getCollection();

        if($page < 0) $page = 0;
        if($page >= $codesCollection->count()) $page = 0;
        if($codesCollection->isEmpty()){
            $this->say(__("language.selection-empty"));
            return;
        }

        $buttons = [];
        foreach($codesCollection->get($page) as $key => $item){
            $buttons[] = Button::create($item)->value($key);
        }

        if($page != 0 && $codesCollection->count() > 1)
            $buttons[] = Button::create("language.selection.previous")->value("previous");

        if($page < $codesCollection->count() - 1 && $codesCollection->count() > 1)
            $buttons[] = Button::create("language.selection.next")->value("next");

        $question = Question::create(__('language.hint'))
            ->addButtons($buttons);

        return $this->ask($question, function (Answer $answer)use($page) {
            $this->try(function() use($answer, $page){
                $this->performSelection($answer, $page);
            }, function(){
                $this->say(__("unexpected-error"));
            });
        });

    }

    /**
     * @return Collection
     */
    public function getCollection(){
        $languageCodes = (new Language())->getAvailableLanguages();
        return (new Collection($languageCodes))->chunk(self::PAGINATION_CHUNK_SIZE);
    }

    public function performSelection(Answer $answer, int $page){
        if(!$answer->isInteractiveMessageReply()){
            $this->askSelection();
            return;
        }

        $val = $answer->getValue();
        switch($val){
            case "previous":
                $this->askSelection($page - 1);
                break;

            case "next":
                $this->askSelection($page + 1);
                break;

            default:

                $language = new Language();
                if(!$language->setLocale($val)){
                    $this->say(__("language.selection-error"));
                    (new BotManController())->sendMenu($this->bot);
                    break;
                }

                $conversation =
                    \App\Conversation::query()->where("conversation_id", $this->bot->getMessage()->getConversationIdentifier())->first();
                $conversation->setDefaultLocale($val);

                $this->say(__("language.selection.done", ["locale" => $val]));
                (new BotManController())->sendMenu($this->bot);
                break;
        }

    }
}
