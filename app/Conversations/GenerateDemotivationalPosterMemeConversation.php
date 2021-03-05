<?php

namespace App\Conversations;

use App\Bot\Bank\Image\ImagePicker;
use App\Bot\Bank\Text\TextGenerator;
use App\Bot\Image\Meme\DemotivationalPosterMeme;
use App\Conversations\Type\GenerateMemeConversation;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Exception;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManagerStatic;

class GenerateDemotivationalPosterMemeConversation extends GenerateMemeConversation
{
    public function getMemeScreenName(): string {
        return __("demotivational-poster-meme.title");
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
        $textGenerator->setMaxWords(20);

        $text = $textGenerator->generateText();
        $textData = (new Collection($text))->chunk(floor(count($text) / 2))->toArray();
        $topText = implode(" ", $textData[0]);
        $bottomText = implode(" ", $textData[1]);

        $image = $imagePicker->pickRandom();

        $meme = new DemotivationalPosterMeme();
        $meme
            ->setBaseImage(ImageManagerStatic::make($image->getPublicURL()))
            ->setTitle($topText)
            ->setSubtitle($bottomText)
            ->draw();

        $url = $meme->makeTempPublic();
        $message = OutgoingMessage::create(__("generate-meme-conversation.demotivational-poser-meme.done"))
            ->withAttachment(new Image(env('APP_URL') . "/" . $url));

        $this->bot->reply($message);
    }

    public function getStartKeyWord(): string {
        return TextGenerator::ANY_WORD;
    }
}
