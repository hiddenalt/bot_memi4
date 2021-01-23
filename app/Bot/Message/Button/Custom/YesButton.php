<?php


namespace App\Bot\Message\Button\Custom;


use App\Bot\Message\Button\Primitive\PositiveButton;

class YesButton extends PositiveButton {

    const YES_VALUE = "y";

    public function __construct() {
        parent::__construct(__("choice.yes"));
        $this->value(self::YES_VALUE);
    }

}