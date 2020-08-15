<?php


namespace App\Bot\PictureGenerator;


use Intervention\Image\Image;

class DemotivationMeme extends Meme{

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
     * @return DemotivationMeme
     */
    public function setBaseImage(Image $baseImage): DemotivationMeme {
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
     * @return DemotivationMeme
     */
    public function setHeader(string $header): DemotivationMeme {
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
     * @return DemotivationMeme
     */
    public function setSubtitle(string $subtitle): DemotivationMeme {
        $this->subtitle = $subtitle;
        return $this;
    }





    /**
     * Drawing the meme
     * @return DemotivationMeme
     */
    public function draw() {



        return $this;
    }

    public function getType(): string {
        return "demotivation";
    }
}