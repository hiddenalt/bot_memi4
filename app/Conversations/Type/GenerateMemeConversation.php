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

    const LAST_SELECTED_BANKS_STORAGE_KEY = "meme_generator.last_selected_banks";

    use ConversationProxy;

    abstract public function getStartKeyWord(): string;
    abstract public function getMemeScreenName(): string;

    /** @var Bank[] $usedBanks */
    public array $usedBanks = [];

    const SCREEN_MENU = "menu";
    const SCREEN_BANKS = "banks";

    public string $currentScreen = self::SCREEN_MENU;

    public function run() {


        // Load last-selected bank list
        $data = $this->getBot()->userStorage()->find($this::LAST_SELECTED_BANKS_STORAGE_KEY);
        if(isset($data) && $data != null) {
            $this->usedBanks = [];
            foreach($data as $bank) {
                $bank = Bank::ofId($bank)->first();
                if($bank != null) $this->usedBanks[] = $bank;
            }
        }


        if(count($this->usedBanks) <= 0)
            $this->usedBanks[] = Bank::ofId(1)->first();

        switch($this->currentScreen){
            case self::SCREEN_MENU:
                $this->showMenu();
                break;

            case self::SCREEN_BANKS:
                $this->selectBanks();
                break;
        }

    }

    public function showMenu(){

        $this->currentScreen = self::SCREEN_MENU;

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

        $this->currentScreen = self::SCREEN_BANKS;

        $collection = collect($this->usedBanks);
        $chunks = $collection->chunk($this->chunkSize);

        $question = Question::create(__("generate-meme-conversation.selecting-banks", [
            "page" => ($page + 1),
            "max_page" => ($chunks->count() + 1)
        ]));

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
                    $this->removeBank($pos);
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

    /**
     * @param array<Bank> ...$banks
     */
    public function addBank(...$banks){
        if($banks instanceof Bank) return;

        foreach($banks as $bank){
            $this->usedBanks[] = $bank;
        }
    }

    public function removeBank(int $pos){
        if(isset($this->usedBanks[$pos])) unset($this->usedBanks[$pos]);
    }


    /*
     * Save last selected bank list to local storage
     */
    public function beforeGoBack() {
        $storage = $this->getBot()->userStorage();

        $storage->delete($this::LAST_SELECTED_BANKS_STORAGE_KEY);
        $storage->save(collect($this->usedBanks)->map(function ($item){
            return $item["id"];
        })->toArray(), $this::LAST_SELECTED_BANKS_STORAGE_KEY);
    }

    abstract public function generateMeme();

}