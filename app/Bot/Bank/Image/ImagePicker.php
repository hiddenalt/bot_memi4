<?php


namespace App\Bot\Bank\Image;


use App\Bot\Bank\BankRelated;
use App\Picture;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ImagePicker extends BankRelated {

    /**
     * @return Builder|Model|object|null
     */
    public function pickRandom(){
        return Picture::query()->whereIn("bank_id", array_keys($this->targetBanks))->inRandomOrder()->first();
    }

    // TODO: pick by sentence (generated text)

}