<?php


namespace App\Bot\Message\Button\Custom;


use App\Bot\Message\Button\Primitive\PrimaryButton;

class SkipButton extends PrimaryButton {

    const SKIP_VALUE = "skip";

    public function __construct() {
        parent::__construct(__("skip"));
        $this->value(self::SKIP_VALUE);
    }

}