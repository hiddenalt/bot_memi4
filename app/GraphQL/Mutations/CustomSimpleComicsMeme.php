<?php

namespace App\GraphQL\Mutations;

use App\Bot\Image\Meme\SimpleComicsMeme;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManagerStatic;

class CustomSimpleComicsMeme extends CustomMemeMutation
{
    public function prepareMeme(array $args) {
        $this->meme = new SimpleComicsMeme();

        /** @var UploadedFile $file */
        $image1 = $args['image1'];
        $image2 = $args['image2'];
        $image3 = $args['image3'];
        $image4 = $args['image4'];

        $this->meme->setFrameLabel1($args["label1"] ?? "");
        $this->meme->setFrameLabel2($args["label2"] ?? "");
        $this->meme->setFrameLabel3($args["label3"] ?? "");
        $this->meme->setFrameLabel4($args["label4"] ?? "");

        $this->meme->setFrameImage1(ImageManagerStatic::make($image1->getRealPath()));
        $this->meme->setFrameImage2(ImageManagerStatic::make($image2->getRealPath()));
        $this->meme->setFrameImage3(ImageManagerStatic::make($image3->getRealPath()));
        $this->meme->setFrameImage4(ImageManagerStatic::make($image4->getRealPath()));

        $this->meme->draw();

        return new \App\GraphQL\Types\SimpleComicsMeme([
            "url" => $this->makePublicURLFromPath($this->meme->makeTempPublic()),
            "label1" => $this->meme->getFrameLabel1(),
            "label2" => $this->meme->getFrameLabel2(),
            "label3" => $this->meme->getFrameLabel3(),
            "label4" => $this->meme->getFrameLabel4(),
        ]);
    }
}
