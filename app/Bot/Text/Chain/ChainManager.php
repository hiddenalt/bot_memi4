<?php


namespace App\Bot\Text;


use App\Bank;
use App\Bot\Text\Chain\DraftChain;
use App\Chain;
use App\Word;
use Exception;
use Illuminate\Support\Facades\DB;

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

        // TODO: optimize SQL request

        $count = count($source);
        for($i = 0; $i < $count; $i++){
            $currentWord = $source[$i] ?? "";
            $nextWord = $source[$i + 1] ?? "";

            switch($i){
                // Learn the pair
                default:
                    $this->learn($currentWord, $nextWord);
                    break;

                // Define the end of the sentence
                case $count - 1:
                    preg_match("/^(.*[^.!?])([.!?]|!{3}|\?!|!\?)$/i", $currentWord, $matches);
                    $this->learn($matches[1] ?? $currentWord, $matches[2] ?? "");
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

            $targetModel = Word::query()->firstOrCreate([
                "bank_id" => $bank->id,
                "text" => $target
            ]);

            $nextModel = Word::query()->firstOrCreate([
                "bank_id" => $bank->id,
                "text" => $next
            ]);

            Chain::query()->firstOrCreate([
                "bank_id" => $bank->id,
                "target" => $targetModel->id,
                "next" => $nextModel->id
            ]);
        }
    }


    /**
     * @return DraftChain
     * @throws Exception
     */
    public function pickRandomUnregisteredPair(){
        // TODO: SQL request optimization

        try {
            DB::statement("SET @rows = 0");
            DB::statement("SET @bank_ids = ?", [implode(",", $this->targetBanks)]);
            $pair = DB::select('
                SELECT
                    *, (@rows := @rows + 1) AS rows_count
                FROM
                    (
                        SELECT *, (@rows := @rows + 1) AS id
                        FROM
                        (
                            SELECT
                                id as target_id,
                                text as target_text
                            FROM
                                wordbank AS tbl
                            WHERE
                                FIND_IN_SET(tbl.bank_id, @bank_ids)
                            ORDER BY tbl.id
                        ) AS res1,
                        (
                            SELECT
                                id as next_id,
                                text as next_text
                            FROM
                                wordbank AS tbl
                            WHERE
                                FIND_IN_SET(tbl.bank_id, @bank_ids)
                            ORDER BY tbl.id
                        ) AS res2
                        WHERE
                            CONCAT(res1.target_id, " ", res2.next_id) NOT IN(SELECT CONCAT(target, " ", next) FROM markov_chain)
                    ) unexisted_pairs
                
                WHERE
                    unexisted_pairs.id >= (SELECT RAND() * MAX(@rows))
                ORDER BY unexisted_pairs.next_id
                LIMIT 1
            ');
            if(count($pair) <= 0 || !isset($pair[0]) || !isset($pair[0]->target_id) || !isset($pair[0]->next_id))
                return null;

            return new DraftChain((int)$pair[0]->target_id, (int)$pair[0]->next_id);
        } catch (Exception $e) {
            throw $e;
        }
    }


}