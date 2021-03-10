<?php

namespace App\Conversations;

use App\Bot\Bank\Image\ImagePicker;
use App\Bot\Bank\Text\TextGenerator;
use App\Bot\Image\Meme\SimpleComicsMeme;
use App\Conversations\Type\GenerateMemeConversation;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Exception;
use Intervention\Image\ImageManagerStatic;

class GenerateSimpleComicsMemeConversation extends GenerateMemeConversation
{
    public function getMemeScreenName(): string {
        return __("4-frame-comics-meme.title");
    }

    /**
     * @throws Exception
     */
    public function generateMeme() {

        $imagePicker = new ImagePicker($this->usedBanks);
        $textGenerator = new TextGenerator($this->usedBanks);

        if(!$textGenerator->hasAnyWords()){
            $this->say(__("generate-meme.no-words-found"));
            return;
        }

        $textGenerator->setStartWith($this->getStartKeyWord());
        $textGenerator->setMaxWords(6);

        $labels = [];
        $images = [];
        for($i = 0; $i < 4; $i++){
            $labels[] = implode(" ", $textGenerator->generateText());
            $images[] = $imagePicker->pickRandom();
        }

        $meme = new SimpleComicsMeme();
        $meme
            ->setFrameImage1(ImageManagerStatic::make($images[0]->getPublicURL()))
            ->setFrameImage2(ImageManagerStatic::make($images[1]->getPublicURL()))
            ->setFrameImage3(ImageManagerStatic::make($images[2]->getPublicURL()))
            ->setFrameImage4(ImageManagerStatic::make($images[3]->getPublicURL()))
            ->setFrameLabel1($labels[0])
            ->setFrameLabel2($labels[1])
            ->setFrameLabel3($labels[2])
            ->setFrameLabel4($labels[3])
            ->draw();

        $url = $meme->makeTempPublic();
        $message = OutgoingMessage::create(__("generate-meme-conversation.simple-comics-poster-meme.done"))
            ->withAttachment(new Image(env('APP_URL') . "/" . $url));

        $this->bot->reply($message);
    }

    public function getStartKeyWord(): string {
        return TextGenerator::ANY_WORD;
    }
}
