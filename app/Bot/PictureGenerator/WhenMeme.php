<?php


namespace App\Bot\PictureGenerator;


use Illuminate\Support\Facades\Log;
use Intervention\Image\AbstractFont;
use Intervention\Image\Gd\Font;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class WhenMeme extends Meme {

    /**
     * @var Image
     */
    protected Image $baseImage;
    /**
     * @var string
     */
    protected string $topText = "Top text";
    /**
     * @var string
     */
    protected string $bottomText = "Bottom text";

    /**
     * WhenMeme constructor.
     */
    public function __construct() {
        parent::__construct(ImageManagerStatic::canvas(800, 600, '#fff'));
    }


    /**
     * @return Image
     */
    public function getBaseImage(): Image {
        return $this->baseImage;
    }

    /**
     * @param Image $baseImage
     * @return WhenMeme
     */
    public function setBaseImage(Image $baseImage): WhenMeme {
        $this->baseImage = $baseImage;
        return $this;
    }

    /**
     * @return string
     */
    public function getTopText(): string {
        return $this->topText;
    }

    /**
     * @param string $topText
     * @return WhenMeme
     */
    public function setTopText(string $topText): WhenMeme {
        $this->topText = $topText;
        return $this;
    }

    /**
     * @return string
     */
    public function getBottomText(): string {
        return $this->bottomText;
    }

    /**
     * @param string $bottomText
     * @return WhenMeme
     */
    public function setBottomText(string $bottomText): WhenMeme {
        $this->bottomText = $bottomText;
        return $this;
    }



    /**
     * Drawing the meme
     * @return WhenMeme
     */
    public function draw() {
        $image = $this->image;

        // Resize to the meme's width & height
        $this->baseImage->resize($image->width(), $image->height());
        $image->insert($this->baseImage);

        $size = 40;
        $offsetY = 10;
        $offset = 2;

        // Draw top text
        $this->drawTextWithShadowByLines(
            $this->image,
            $this->topText,
            $image->width() / 2,
            $offsetY,
            $size,
            "impact.ttf",
            "#fff",
            "#000",
            "center",
            "top",
            $offset
        );

        // Draw bottom text
        $this->drawTextWithShadowByLines(
            $this->image,
            $this->bottomText,
            $image->width() / 2,
            $image->height() - $offsetY,
            $size,
            "impact.ttf",
            "#fff",
            "#000",
            "center",
            "bottom",
            $offset
        );


        return $this;
    }

    public function getType(): string {
        return "when";
    }
}