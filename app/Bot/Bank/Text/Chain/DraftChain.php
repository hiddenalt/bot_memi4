<?php


namespace App\Bot\Bank\Text\Chain;


class DraftChain {

    public int $target = -1;
    public int $next = -1;

    /**
     * DraftChain constructor.
     * @param int $target
     * @param int $next
     */
    public function __construct(int $target, int $next) {
        $this->target = $target;
        $this->next = $next;
    }


}