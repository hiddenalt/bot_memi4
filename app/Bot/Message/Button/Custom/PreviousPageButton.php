<?php


namespace App\Bot\Message\Button\Custom;


use App\Bot\Message\Button\Primitive\SecondaryButton;

class PreviousPageButton extends SecondaryButton {

    const PREVIOUS_PAGE_VALUE = "previous-page";

    public function __construct() {
        parent::__construct(__('pagination.previous'));
        $this->value(self::PREVIOUS_PAGE_VALUE);
    }

}