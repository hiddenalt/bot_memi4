<?php


namespace App\Bot\Message\Button\Custom;


use App\Bot\Message\Button\Primitive\NegativeButton;

class BackButton extends NegativeButton {

    const BACK_VALUE = "back";

    public function __construct() {
        parent::__construct(__("menu.back"));
        $this->value(self::BACK_VALUE);
    }

}