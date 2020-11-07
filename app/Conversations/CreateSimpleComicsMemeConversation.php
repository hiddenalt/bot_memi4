<?php

namespace App\Conversations;

use App\Bot\Image\Meme\SimpleComicsMeme;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Intervention\Image\ImageManagerStatic;

class CreateSimpleComicsMemeConversation extends Conversation
{

    public string $pictureUrl1 = "";
    public string $pictureUrl2 = "";
    public string $pictureUrl3 = "";
    public string $pictureUrl4 = "";
    public string $label1 = "";
    public string $label2 = "";
    public string $label3 = "";
    public string $label4 = "";


    public function askPicture1() {
        return $this->askForImages(__("create-comics-meme-conversation.step-1"),
            /** @param Image[] $images */
            function($images){

                $this->pictureUrl1 = $images[0]->getUrl();

                $this->askPicture2();
            },
            function(Answer $answer){
                $this->askPicture1();
            }
        );
    }

    public function askPicture2() {
        return $this->askForImages(__("create-comics-meme-conversation.step-2"),
            /** @param Image[] $images */
            function($images){

                $this->pictureUrl2 = $images[0]->getUrl();

                $this->askPicture3();
            },
            function(Answer $answer){
                $this->askPicture2();
            }
        );
    }

    public function askPicture3() {
        return $this->askForImages(__("create-comics-meme-conversation.step-3"),
            /** @param Image[] $images */
            function($images){

                $this->pictureUrl3 = $images[0]->getUrl();

                $this->askPicture4();
            },
            function(Answer $answer){
                $this->askPicture3();
            }
        );
    }

    public function askPicture4() {
        return $this->askForImages(__("create-comics-meme-conversation.step-4"),
            /** @param Image[] $images */
            function($images){

                $this->pictureUrl4 = $images[0]->getUrl();

                $this->askLabel1();
            },
            function(Answer $answer){
                $this->askPicture4();
            }
        );
    }



    public function askLabel1(){
        $question = Question::create(__("create-comics-meme-conversation.step-5"));

        return $this->ask($question, function (Answer $answer) {

            $text = trim($answer->getMessage()->getText());

            if($text == ""){
                $this->askLabel1();
            } else {
                $this->label1 = $text;
                $this->askLabel2();
            }

        });
    }

    public function askLabel2(){
        $question = Question::create(__("create-comics-meme-conversation.step-6"));

        return $this->ask($question, function (Answer $answer) {

            $text = trim($answer->getMessage()->getText());

            if($text == ""){
                $this->askLabel2();
            } else {
                $this->label2 = $text;
                $this->askLabel3();
            }

        });
    }

    public function askLabel3(){
        $question = Question::create(__("create-comics-meme-conversation.step-7"));

        return $this->ask($question, function (Answer $answer) {

            $text = trim($answer->getMessage()->getText());

            if($text == ""){
                $this->askLabel3();
            } else {
                $this->label3 = $text;
                $this->askLabel4();
            }

        });
    }

    public function askLabel4(){
        $question = Question::create(__("create-comics-meme-conversation.step-8"));

        return $this->ask($question, function (Answer $answer) {

            $text = trim($answer->getMessage()->getText());

            if($text == ""){
                $this->askLabel4();
            } else {
                $this->label4 = $text;
                $this->sendMeme();
            }

        });
    }

    public function sendMeme(){
        $this->bot->types();

        $meme = new SimpleComicsMeme();

        // TODO: custom options
        $meme
            ->setFrameImage1(ImageManagerStatic::make($this->pictureUrl1))
            ->setFrameImage2(ImageManagerStatic::make($this->pictureUrl2))
            ->setFrameImage3(ImageManagerStatic::make($this->pictureUrl3))
            ->setFrameImage4(ImageManagerStatic::make($this->pictureUrl4))
            ->setFrameLabel1($this->label1)
            ->setFrameLabel2($this->label2)
            ->setFrameLabel3($this->label3)
            ->setFrameLabel4($this->label4)
            ->draw();

        $url = $meme->makeTempLink();
        $message = OutgoingMessage::create(__("create-comics-meme-conversation.done"))
            ->withAttachment(new Image(env('APP_URL') . "/" . $url));

        $this->bot->reply($message);
        $this->bot->startConversation(new CreateMemeMenuConversation(null));
    }

    /**
     * Start the conversation.
     */
    public function run()
    {
        $this->askPicture1();
    }
}
