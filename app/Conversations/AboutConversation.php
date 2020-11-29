<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\VK\VkCommunityCallbackDriver;

class AboutConversation extends BackFunctionConversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $attachment = new Image(env("APP_URL") . "/logo.jpg");

        $message = OutgoingMessage::create(__("about", [
            "app_name" => env("APP_NAME"), // if needed by translation
            "version" => app('pragmarx.version')->format("full"),
            "php_version" => phpversion(),

            "wiki" => env("WELCOME_PAGE_WIKI"),
            "github" => env("WELCOME_PAGE_GITHUB_URL"),
            "vk" => env("WELCOME_PAGE_VK_URL"),
            "tg" => env("WELCOME_PAGE_TELEGRAM_URL"),
        ]));
            $message->withAttachment($attachment);

        $payload = [];

        // Don't show attached weird links
        if($this->bot->getDriver() instanceof VkCommunityCallbackDriver)
            $payload["dont_parse_links"] = true;

        $this->bot->reply($message, $payload);
        $this->moveBack();
    }
}
