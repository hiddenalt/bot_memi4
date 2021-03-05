<?php

namespace App\GraphQL\Mutations;

use App\Bot\Image\Meme\WhenMeme;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic;

class CustomWhenMeme extends CustomMemeMutation
{
    public function prepareMeme(array $args) {
        $this->meme = new WhenMeme();

        /** @var UploadedFile $file */
        $file = $args['image'];

        $this->meme->setTopText($args["top_text"] ?? "");
        $this->meme->setBottomText($args["bottom_text"] ?? "");

        $this->meme->setBaseImage(ImageManagerStatic::make($file->getRealPath()));

        $this->meme->draw();

        return new \App\GraphQL\Types\WhenMeme([
            "url" => $this->makePublicURLFromPath($this->meme->makeTempPublic()),
            "top_text" => $this->meme->getTopText(),
            "bottom_text" => $this->meme->getBottomText(),
        ]);
    }
}
