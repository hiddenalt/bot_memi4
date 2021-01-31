<?php


namespace App\Conversations\Type;


use App\Bank;
use App\Bot\Conversation\ConversationProxy;
use App\Bot\Message\Button\Custom\BackButton;
use App\Bot\Message\Button\Custom\NoButton;
use App\Bot\Message\Button\Custom\YesButton;
use App\Bot\Message\Button\Primitive\PositiveButton;
use App\Bot\Message\Button\Primitive\PrimaryButton;
use App\Bot\Message\Button\Primitive\SecondaryButton;
use App\Conversations\BackFunctionConversation;
use App\Conversations\SelectBankFromList;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

abstract class GenerateMemeConversation extends BackFunctionConversation {

    use ConversationProxy;

    abstract public function getStartKeyWord(): string;
    abstract public function getMemeScreenName(): string;

    /** @var Bank[] $usedBanks */
    public array $usedBanks = [];

    public function run() {
        if(count($this->usedBanks) <= 0)
            $this->usedBanks[] = Bank::ofId(1)->first();

        $this->showMenu();
    }

    public function showMenu(){
        $question = Question::create(__('generate-meme.hint', [
            "screenName" => $this->getMemeScreenName()
        ]))
            ->addButtons([
                Button::create(__('generate-meme.execute'))->value('execute')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create(__('generate-meme.edit-bank-list'))->value('edit-bank-list')->additionalParameters([
                    "color" => "primary"
                ]),
                Button::create(__('menu.back'))->value('back')->additionalParameters([
                    "color" => "secondary"
                ])
            ]);
        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $selectedValue = $answer->getValue();

                switch($selectedValue){

                    case "back":
                        $this->moveBack();
                        break;

                    case "execute":
                        $this->tryOrSayErrorAndMoveBack(function (){
                            $this->generateMeme();
                            $this->showMenu();
                        }, __("generate-meme-conversation.generating-error"));
                        break;

                    case "edit-bank-list":
                        $this->selectBanks();
                        break;

                    default:
                        $this->bot->reply(__('generate-meme-conversation.unknown-command'));
                        $this->showMenu();

                        break;
                }
            } else {
                $this->showMenu();
            }
        });
    }

    public int $chunkSize = 5;

    public function selectBanks($page = 0){

        $collection = collect($this->usedBanks);
        $chunks = $collection->chunk($this->chunkSize);

        $question = Question::create(__("generate-meme-conversation.selecting-banks"));

        if($page < 0) $page = 0;
        if($page >= $chunks->count()) $page = 0;
        if(!$chunks->isEmpty()){
            foreach($chunks->get($page) as $key => $item){
                /** @var Bank $item */
                $question->addButton(PrimaryButton::create($item->title)->value("delete-{$key}"));
            }

            if($page != 0 && $chunks->count() > 1)
                $question->addButton(SecondaryButton::create(__("pagination.previous"))->value("previous"));

            if($page < $chunks->count() - 1 && $chunks->count() > 1)
                $question->addButton(SecondaryButton::create(__("pagination.next"))->value("next"));
        }



        $question->addButton( (new PositiveButton(__("generate-meme-conversation.add-bank")))->value("add"));
        $question->addButton(new BackButton());

        $this->ask($question, function(Answer $answer)use($page){

            if($answer->isInteractiveMessageReply()){
                switch($answer->getValue()){
                    case BackButton::BACK_VALUE:
                        $this->showMenu();
                        return;
                        break;

                    case "add":
                        $this->bot->startConversation(new SelectBankFromList($this));
                        return;
                        break;

                    case "previous":
                        $this->selectBanks($page - 1);
                        break;

                    case "next":
                        $this->selectBanks($page + 1);
                        break;
                }

                preg_match('/delete-([0-9+])/', $answer->getValue(), $matches);
                if(isset($matches[1]) and isset($this->usedBanks[$matches[1]])) {
                    return $this->confirmRemoval($matches[1]);
                }

                return $this->showMenu($page);
            }



        });
    }

    public function confirmRemoval(int $pos){
        if(!isset($this->usedBanks[$pos])) return;
        $bank = $this->usedBanks[$pos];

        $question = Question::create(__("generate-meme-conversation.confirm-removal", [
            "title" => $bank->title
        ]));

        $question->addButton(new YesButton());
        $question->addButton(new NoButton());
        $question->addButton(new BackButton());

        $this->ask($question, function(Answer $answer) use($bank, $pos){
            if(!$answer->isInteractiveMessageReply()) return $this->confirmRemoval($bank);

            switch($answer->getValue()){
                case YesButton::YES_VALUE:
                    unset($this->usedBanks[$pos]);
                    $this->showMenu();
                    break;

                case BackButton::BACK_VALUE:
                case NoButton::NO_VALUE:
                    $this->showMenu();
                    break;

                default:
                    return $this->confirmRemoval($bank);
                    break;
            }
        });

    }

    abstract public function generateMeme();

}