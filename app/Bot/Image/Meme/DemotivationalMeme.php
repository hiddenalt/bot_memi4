<?php


namespace App\Bot\Image\Meme;


use Intervention\Image\Image;

class DemotivationalMeme extends Meme{

    /**
     * @var Image
     */
    protected Image $baseImage;
    /**
     * @var string
     */
    protected string $header = "Title";
    /**
     * @var string
     */
    protected string $subtitle = "Subtitle";

    /**
     * @param Image $baseImage
     * @return DemotivationalMeme
     */
    public function setBaseImage(Image $baseImage): DemotivationalMeme {
        $this->baseImage = $baseImage;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeader(): string {
        return $this->header;
    }

    /**
     * @param string $header
     * @return DemotivationalMeme
     */
    public function setHeader(string $header): DemotivationalMeme {
        $this->header = $header;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubtitle(): string {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     * @return DemotivationalMeme
     */
    public function setSubtitle(string $subtitle): DemotivationalMeme {
        $this->subtitle = $subtitle;
        return $this;
    }





    /**
     * Drawing the meme
     * @return DemotivationalMeme
     */
    public function draw() {



        return $this;
    }

    public function getType(): string {
        return "demotivation";
    }
}