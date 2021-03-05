<?php

namespace App\GraphQL\Mutations;

use App\Bot\Image\Meme\DemotivationalPosterMeme;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic;

class CustomDemotivationalPosterMeme extends CustomMemeMutation
{

    public function prepareMeme(array $args) {
        $this->meme = new DemotivationalPosterMeme();

        /** @var UploadedFile $file */
        $file = $args['image'];

        $this->meme->setTitle($args["title_text"] ?? "");
        $this->meme->setSubtitle($args["subtitle_text"] ?? "");
        $this->meme->setBaseImage(ImageManagerStatic::make($file->getRealPath()));

        $this->meme->draw();

        return new \App\GraphQL\Types\DemotivationalPosterMeme([
            "url" => $this->makePublicURLFromPath($this->meme->makeTempPublic()),
            "title_text" => $this->meme->getTitle(),
            "subtitle_text" => $this->meme->getSubtitle()
        ]);
    }
}
