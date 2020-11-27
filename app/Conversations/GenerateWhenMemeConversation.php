<?php

namespace App\Conversations;

use App\Bot\Bank\Image\ImagePicker;
use App\Bot\Bank\Text\TextGenerator;
use App\Bot\Image\Meme\WhenMeme;
use App\Conversations\Type\GenerateMemeConversation;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManagerStatic;

class GenerateWhenMemeConversation extends GenerateMemeConversation
{
    public function getMemeScreenName(): string {
        return __("when-meme.title");
    }

    /**
     * @throws \Exception
     */
    public function generateMeme() {

        $imagePicker = new ImagePicker($this->usedBanks);
        $textGenerator = new TextGenerator($this->usedBanks);

        if(!$textGenerator->hasAnyWords()){
            $this->say(__("generate-meme.no-words-found"));
            return;
        }

        $textGenerator->setStartWith($this->getStartKeyWord());
        $textGenerator->setMaxWords(20);

        $text = $textGenerator->generateText();
        $textData = (new Collection($text))->chunk(floor(count($text) / 2))->toArray();
        $topText = implode(" ", $textData[0]);
        $bottomText = implode(" ", $textData[1]);

        $image = $imagePicker->pickRandom();

        $meme = new WhenMeme();
        $meme
            ->setBaseImage(ImageManagerStatic::make($image->getPublicURL()))
            ->setTopText($topText)
            ->setBottomText($bottomText)
            ->draw();

        $url = $meme->makeTempLink();
        $message = OutgoingMessage::create(__("generate-meme-conversation.when-meme.done"))
            ->withAttachment(new Image(env('APP_URL') . "/" . $url));

        $this->bot->reply($message);
    }

    public function getStartKeyWord(): string {
        return __("generate-meme.regexp.when-word");
    }
}
