<?php

namespace App\Conversations;

use App\Bot\PictureGenerator\WhenMeme;
use App\TempLink;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic;

/**
 * Class CreateWhenMemeConversation
 * @package App\Conversations
 * TODO: translate conversation to English
 */
class CreateWhenMemeConversation extends Conversation {

    public string $pictureUrl = "";
    public string $topText = "";
    public string $bottomText = "";

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run(){
        $this->askPicture();
    }

    public function askPicture(){
        return $this->askForImages("[Шаг 1/3]\nПришли картинку мема.",
            /** @param Image[] $images */
            function($images){
                $this->pictureUrl = $images[0]->getUrl();
                $this->askTopString();
            },
            function(Answer $answer){
                $this->askPicture();
            }
        );
    }

    public function askTopString(){
        $question = Question::create("[Шаг 2/3]\nПришли верхний текст мема.");

        return $this->ask($question, function (Answer $answer) {

            $text = trim($answer->getMessage()->getText());

            if($text == ""){
                $this->askTopString();
            } else {
                $this->topText = $text;
                $this->askBottomString();
            }

        });
    }

    public function askBottomString(){
        $question = Question::create("[Шаг 3/3]\nПришли нижний текст мема.");

        return $this->ask($question, function (Answer $answer) {

            $text = trim($answer->getMessage()->getText());

            if($text == ""){
                $this->askTopString();
            } else {
                $this->bottomText = $text;
                $this->sendMeme();
            }

        });
    }



    public function sendMeme(){
        $this->bot->types();

        $meme = new WhenMeme();

        $meme
            ->setBaseImage(ImageManagerStatic::make($this->pictureUrl))
            ->setTopText($this->topText)
            ->setBottomText($this->bottomText)
            ->draw();

        $url = $meme->makeTempLink();
        $message = OutgoingMessage::create('Готово.')
            ->withAttachment(new Image(env('APP_URL') . "/" . $url));

        $this->bot->reply($message);
        $this->bot->startConversation(new CreateMemeConversation());
    }
}
