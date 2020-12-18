<?php


namespace App\Bot\Message\Button\Primitive;


use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\Drivers\VK\VkCommunityCallbackDriver;

class NativeInteractionButton extends Button {

    const STYLE_DEFAULT = "default";
    const STYLE_PRIMARY = "primary";
    const STYLE_SECONDARY = "secondary";
    const STYLE_POSITIVE = "positive";
    const STYLE_NEGATIVE = "negative";

    protected $text = "button";
    protected $value = "";
    protected $color = self::STYLE_DEFAULT;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        parent::__construct($text);
        $this->text = $text;
        $this->applyStyle();
    }

    public function applyStyle(){
        /** @var BotMan $botman */
        $botman = app("botman");
        switch($botman->getDriver()->getName()){

            // VK
            case VkCommunityCallbackDriver::DRIVER_NAME:
                $vk = [
                    self::STYLE_DEFAULT => "primary",
                    self::STYLE_PRIMARY => "primary",
                    self::STYLE_SECONDARY => "secondary",
                    self::STYLE_POSITIVE => "positive",
                    self::STYLE_NEGATIVE => "negative",
                ];

//                Log::info("wtf: ".$vk[$this->color]." ".$this->color);

                $this->additionalParameters([
                    "color" => $vk[$this->color] ?? "primary"
                ]);

                break;
        }
    }

}