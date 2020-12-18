<?php

namespace App\Conversations;

use App\Bot\Image\Meme\WhenMeme;
use App\Bot\Message\Button\Custom\SkipButton;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Intervention\Image\ImageManagerStatic;

/**
 * Class CreateWhenMemeConversation
 * @package App\Conversations
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
        return $this->askForImages(__("create-when-meme-conversation.step-1"),
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
        $question = Question::create(__("create-when-meme-conversation.step-2"));

        $question->addButton(new SkipButton());

        return $this->ask($question, function (Answer $answer) {

            if($answer->isInteractiveMessageReply() && $answer->getValue() == SkipButton::SKIP_VALUE){
                return $this->askBottomString();
            }

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
        $question = Question::create(__("create-when-meme-conversation.step-3"));

        $question->addButton(new SkipButton());

        return $this->ask($question, function (Answer $answer) {

            if($answer->isInteractiveMessageReply() && $answer->getValue() == SkipButton::SKIP_VALUE){
                return $this->sendMeme();
            }

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
        $message = OutgoingMessage::create(__("create-when-meme-conversation.done"))
            ->withAttachment(new Image(env('APP_URL') . "/" . $url));

        $this->bot->reply($message);
        $this->bot->startConversation(new CreateMemeMenuConversation(null));
    }
}
