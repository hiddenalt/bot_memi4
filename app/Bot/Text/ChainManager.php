<?php


namespace App\Bot\Text;


use App\Bank;
use App\Chain;

class ChainManager {

    const SPACE_DELIMITER = " ";

    /** @var array $targetBanks */
    private array $targetBanks = [];

    /**
     * TextTeach constructor.
     * @param array $targetBanks
     */
    public function __construct(array $targetBanks) {
        $this->targetBanks = $targetBanks;
    }


    /**
     * Push target banks to be set
     * @param array $banks
     */
    public function addTargetBanks(array $banks){
        foreach($banks as $bank) $this->targetBanks[] = $bank;
    }

    /**
     * Set the only bank to be set
     * @param Bank $bank
     */
    public function setTargetBank(Bank $bank){
        $this->targetBanks[0] = $bank;
    }

    /**
     *
     * @return array
     */
    public function getTargetBanks(){
        return $this->targetBanks;
    }



    /**
     * Default learn of text (delimiter = space)
     * @param string $sourceText
     */
    public function learnText(string $sourceText){
        $this->learnFromString($sourceText, self::SPACE_DELIMITER);
    }

    /**
     * Learn the string with custom delimiter
     * @param string $sourceText
     * @param string $delimiter
     */
    public function learnFromString(string $sourceText, string $delimiter = self::SPACE_DELIMITER){
        $sourceTextAsArray = explode($delimiter, $sourceText);
        $this->learnFromArray($sourceTextAsArray);
    }

    /**
     * Learn the data from array
     * @param array $source
     */
    public function learnFromArray(array $source){
        $count = count($source);
        for($i = 0; $i < $count; $i++){
            $currentWord = $sourceTextAsArray[0] ?? "";
            $nextWord = $sourceTextAsArray[$i] ?? "";

            switch($i){
                // Learn the pair
                default:
                    $this->learn($currentWord, $nextWord);
                    break;

                // Define the end of the sentence
                case $count - 1:
                    $this->learn($currentWord, "");
                    break;

                // Define the start of the sentence
                case 0:
                    $this->learn("", $currentWord);
            }
        }
    }

    /**
     * Learn by one pair
     * @param string $target
     * @param string $next
     */
    public function learn(string $target, string $next){
        foreach($this->targetBanks as $bank){
            /** @var Bank $bank */

            // TODO: log learning, "restore" function

            Chain::query()->firstOrCreate([
                "bank_id" => $bank->id,
                "target" => $target,
                "next" => $next
            ]);
        }
    }


}