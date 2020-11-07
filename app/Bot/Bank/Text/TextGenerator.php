<?php


namespace App\Bot\Bank\Text;


use App\Bot\Bank\BankRelated;
use App\Chain;
use App\Word;
use Exception;

class TextGenerator extends BankRelated {

    /** @var string $startWith */
    private string $startWith = "";
    /** @var int $maxWords */
    private int $maxWords = 10;
    /** @var bool $completeSentence */
    private bool $completeSentence = false;

    /**
     * @return bool
     */
    public function hasAnyWords(){
        $bank_ids = array_keys($this->targetBanks);
        $count = Chain::query()->select(["id"])->whereIn("bank_id", $bank_ids)->count();
        return $count > 0;
    }

    /**
     * @return string
     */
    public function getStartWith(){
        return $this->startWith;
    }

    /**
     * @param string $word
     * @return $this
     */
    public function setStartWith(string $word){
        $this->startWith = $word;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxWords(): int {
        return $this->maxWords;
    }

    /**
     * @param int $maxWords
     */
    public function setMaxWords(int $maxWords): void {
        $this->maxWords = $maxWords;
    }

    /**
     * @return bool
     */
    public function isCompleteSentence(): bool {
        return $this->completeSentence;
    }

    /**
     * @param bool $completeSentence
     */
    public function setCompleteSentence(bool $completeSentence): void {
        $this->completeSentence = $completeSentence;
    }



    /**
     * @throws Exception
     */
    public function generateText(){
        $result = [];
        $bank_ids = array_keys($this->targetBanks);

        $startWith = Word::query()->where("text", $this->startWith)->whereIn("bank_id", $bank_ids)->first();
        if($startWith == null)
            throw new Exception("Word startWith = {$this->startWith} not found");

        // TODO: complete sentence generation

        $currentWord = $startWith;
        $result[] = $currentWord->text;
        for($i = 0; $i < $this->maxWords; $i++){
            $chain = Chain::query()
                ->where("target", $currentWord->id)
                ->whereIn("bank_id", $bank_ids)
                ->inRandomOrder()
                ->first();

            if($chain == null){
//                if(\Safe\preg_match("/[!?.]/i", array_last($result), $matches))
//                    $result[] = ".";
                break;
            }

            $nextWord = $chain->next()->first();
            $result[] = $nextWord->text ?? "generic";
            $currentWord = $nextWord;
        }



        // TODO: join the full stop (excl. mark, q. mark, ...) with the last word

        return $result;
    }


}