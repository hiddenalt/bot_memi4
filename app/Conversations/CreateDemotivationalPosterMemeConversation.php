<?php

namespace App\Conversations;

use App\Bot\Image\Meme\DemotivationalMeme;
use App\Bot\Image\Meme\WhenMeme;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Intervention\Image\ImageManagerStatic;

class CreateDemotivationalPosterMemeConversation extends Conversation
{
    public string $pictureUrl = "";
    public string $topText = "";
    public string $bottomText = "";

    public function askPicture(){
        return $this->askForImages(__("create-demotivational-poster-meme-conversation.step-1"),
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
        $question = Question::create(__("create-demotivational-poster-meme-conversation.step-2"));

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
        $question = Question::create(__("create-demotivational-poster-meme-conversation.step-3"));

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

        $meme = new DemotivationalMeme();

        // TODO: custom options
        $meme
            ->setBaseImage(ImageManagerStatic::make($this->pictureUrl))
            ->setTitle($this->topText)
            ->setSubtitle($this->bottomText)
            ->draw();

        $url = $meme->makeTempLink();
        $message = OutgoingMessage::create(__("create-when-meme-conversation.done"))
            ->withAttachment(new Image(env('APP_URL') . "/" . $url));

        $this->bot->reply($message);
        $this->bot->startConversation(new CreateMemeConversation());
    }

    /**
     * Start the conversation.
     *
     * @return void
     */
    public function run(): void{
        $this->askPicture();
    }
}
