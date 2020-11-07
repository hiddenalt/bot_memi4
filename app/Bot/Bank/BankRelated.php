<?php


namespace App\Bot\Bank;


use App\Bank;

class BankRelated {

    /** @var Bank[] $targetBanks */
    protected array $targetBanks = [];

    /**
     * BankRelated constructor.
     * @param array $targetBanks
     */
    public function __construct(array $targetBanks) {
        $this->addTargetBanks($targetBanks);
    }

    /**
     * Push target banks to be set
     * @param array $banks
     */
    public function addTargetBanks(array $banks){
        foreach($banks as $bank) $this->targetBanks[$bank->id ?? 0] = $bank;
    }

    /**
     * Set the only bank to be set
     * @param Bank $bank
     */
    public function setTargetBank(Bank $bank){
        $this->targetBanks = [];
        $this->targetBanks[$bank->id ?? 0] = $bank;
    }

    /**
     *
     * @return array
     */
    public function getTargetBanks(){
        return $this->targetBanks;
    }
}