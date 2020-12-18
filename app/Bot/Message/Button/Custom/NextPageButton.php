<?php


namespace App\Bot\Message\Button\Custom;


use App\Bot\Message\Button\Primitive\SecondaryButton;

class NextPageButton extends SecondaryButton {

    const NEXT_PAGE_VALUE = "next-page";

    public function __construct() {
        parent::__construct(__('pagination.next'));
        $this->value(self::NEXT_PAGE_VALUE);
    }
}