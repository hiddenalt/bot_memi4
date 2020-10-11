<?php


namespace App\Bot\Conversation;


use Closure;
use Exception;
use Illuminate\Support\Facades\Log;

trait ConversationProxy {

    /**
     * Try-catch behaviour for conversations.
     * @param callable $method
     * @param callable $ifError
     * @return mixed
     */
    public function try(callable $method, callable $ifError){
        try {
            return (Closure::bind($method, $this))();
        } catch(Exception $e){
            return (Closure::bind($ifError, $this))($e);
        }
    }

    /**
     * @param callable $method
     * @param string $errorPrefix
     * @return mixed
     */
    public function tryOrSayErrorAndMoveBack(callable $method, string $errorPrefix){
        return $this->try($method, function(Exception $e) use($errorPrefix){
            $this->say($errorPrefix . $e->getMessage());
            Log::error(self::class . ": conversation error: " . $e->getMessage() . ", trace:\n" . $e->getTraceAsString());
            $this->moveBack();
        });
    }

}