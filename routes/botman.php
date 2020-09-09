<?php

use App\Http\Controllers\BotManController;
use App\Http\Middleware\RegisterConversation;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\VK\VkCommunityCallbackDriver;

/** @var BotMan $botman */
$botman = resolve('botman');

/**
 * Listeners
 */

// VK driver-specific confirmation event handler
$botman->group(['driver' => [VkCommunityCallbackDriver::class]], function(BotMan $bot) {
    $bot->on("confirmation", BotManController::class . "@VKConfirmationToken");
});

$botman->middleware->received(new RegisterConversation());

/**
 * Command listeners
 */

// Menu
// TODO: improve patterns for cancelling the conversations (see translation files)
$botman->hears(
    __("pattern.cancel-conversation", [".*(cancel|back)"]),
    BotManController::class . "@sendMenu"
)->stopsConversation();

$botman->hears(
    __("pattern.menu-start", [".*(start|menu|main menu|main_menu)"]),
    BotManController::class . "@sendMenu"
);


// Generating the meme
$botman->hears(
    __("pattern.generate-meme-conversation"),
    BotManController::class . "@startGenerateMemeConversation"
);

// Manually created memes by users
$botman->hears(
    __("pattern.create-meme-conversation"),
    BotManController::class . "@startCreateMemeConversation"
);


$botman->fallback('App\Http\Controllers\FallbackController@index');