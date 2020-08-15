<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
//use BotMan\BotMan\Messages\Attachments\Location;

// TODO: make logic non-static
// TODO: translate routes to English

/** @var BotMan $botman */
$botman = resolve('botman');

/**
 * Listeners
 */

$botman->on("confirmation", function($payload, Botman $bot){
    echo("b8818710");
});



/** ================================================================================================================= */
$botman->middleware->received(new \App\Http\Middleware\RegisterConversation());
/** ================================================================================================================= */


/**
 * Command listeners
 */

// Menu
/**
 * @param BotMan $bot
 */
$menu = function(BotMan $bot) {

    $question = Question::create("Меню")
    ->addButtons([
        Button::create('☺ Сгенерировать мем')->value("generate_meme")->additionalParameters([
            "color" => "positive"
        ]),
        Button::create('🖌 Создать мем')->value("create_meme")->additionalParameters([
            "color" => "positive"
        ]),
        Button::create('⚙ Настройки')->value("settings")->additionalParameters([
            "color" => "primary"
        ])
    ]);

    $bot->reply($question);
};
$botman->hears('.*(завершить|закрыть|назад|отмена|back)', $menu)->stopsConversation();
$botman->hears('.*(меню|start|начать)', $menu);


// Generating the meme
$botman->hears('.*((сгенерировать|сгенерируй|генерь|сделай|сделать|придумай|придумать|давай|ебаш|хуярь).*(мем|мемас|мемчик|мемасик|мэм|мэмасик|мэмчик)|generate_meme)',
    function(BotMan $bot) {
        $bot->startConversation(new \App\Conversations\GenerateMemeConversation());
    }
);

// Manually creating memes
$botman->hears('.*((создать|сделать|собрать|загрузить|новый|мой|свой).*(мем|мемас|мемчик|мемасик|мэм|мэмасик|мэмчик)|create_meme)', function(BotMan $bot) {
    $bot->startConversation(new \App\Conversations\CreateMemeConversation());
});


/** ================================================================================================================= */
$botman->fallback('App\Http\Controllers\FallbackController@index');