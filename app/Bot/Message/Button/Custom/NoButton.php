<?php


namespace App\Bot\Message\Button\Custom;


use App\Bot\Message\Button\Primitive\PositiveButton;

class NoButton extends PositiveButton {

    const NO_VALUE = "n";

    public function __construct() {
        parent::__construct(__("choice.no"));
        $this->value(self::NO_VALUE);
    }

}