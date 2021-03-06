<?php


namespace App\Bot\Bank\Text;


use App\Bot\Bank\BankRelated;
use App\Bot\Bank\Text\Chain\ChainManager;
use App\Chain;
use App\Word;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TextGenerator extends BankRelated {


    const ANY_WORD = "";

    /** @var string $startWith */
    private string $startWith = "";
    /** @var int $maxWords */
    private int $maxWords = 10;
    /** @var bool $completeSentence */
    private bool $completeSentence = false;
    /** @var bool $uppercaseFirst */
    private bool $uppercaseFirst = true;

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
     * @return bool
     */
    public function isUppercaseFirst(): bool {
        return $this->uppercaseFirst;
    }

    /**
     * @param bool $uppercaseFirst
     */
    public function setUppercaseFirst(bool $uppercaseFirst): void {
        $this->uppercaseFirst = $uppercaseFirst;
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

        $currentWord = $startWith;
        $result[] = $currentWord->text;
        for($i = 0; $i < $this->maxWords; $i++){
            $chain = Chain::query()
                ->where("target", $currentWord->id)
                ->whereIn("bank_id", $bank_ids)
                ->inRandomOrder()
                ->first();

            if($chain == null)
                break;

            $nextWord = $chain->next()->first();

            $preparedWord = $this->performPair($currentWord, $nextWord);
            if($preparedWord != null)
                $result[] = $preparedWord;

            $currentWord = $nextWord;
        }

        // Put random sentence punctuation mark if not generated by chain
        if(!Str::endsWith(last($result), ChainManager::SENTENCE_END))
            $result[array_key_last($result)] .= (new Collection(ChainManager::SENTENCE_END))->random(1)->toArray()[0];

        return $result;
    }




    /**
     * @param Model $currentWord
     * @param Model $nextWord
     * @return string|null
     */
    public function performPair(Model $currentWord, Model $nextWord){
        $text = $nextWord->text;

        // Random punctuation marks
        if($text == ChainManager::END_OF_SENTENCE)
            return null;

        // Return word value
        if($text != null){
            // Uppercase only the first character of the first word
            if($this->uppercaseFirst)
                return ($currentWord->text == ChainManager::NEW_SENTENCE) ?
                    Str::ucfirst(Str::lower($text)) : Str::lower($text);

            return $text;
        }

        // Return if something went wrong
        return null;
    }


}