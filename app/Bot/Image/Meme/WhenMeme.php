<?php


namespace App\Bot\Image\Meme;


use App\Bot\Image\Brush\TextBrush;
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
        $image = $this->canvas;

        // Resize to the meme's width & height
        $this->baseImage->resize($image->width(), $image->height());
        $image->insert($this->baseImage);

        $maxLinesTopText            = 5; // Max lines for top text
        $maxLinesBottomText         = 5; // Max lines for bottom text
        $widthWrap                  = $image->width() - 50;

        $size = 40;
        $offsetY = 5;
        $offset = 5;

        // Text brush for drawing texts
        $textBrush = new TextBrush($image);

        // Configure common for top & bottom texts
        $textBrush->setX($image->width() / 2);
        $textBrush->setY($offsetY);
        $textBrush->setSize($size);
        $textBrush->setFontFile("impact.ttf");
        $textBrush->setTextColor("#fff");
        $textBrush->setShadowColor("#000");
        $textBrush->setAlign("center");
        $textBrush->setVerticalAlign("top");
        $textBrush->setLinesOffset($offset);

        // Wrapping the texts
        $textBrush->setWrapText(true);
        $textBrush->setWrapTextMaxWidth($widthWrap);
        $textBrush->setWrapTextMaxLines($maxLinesTopText);

        $textBrush->setText($this->topText);

        // Draw top text
        $textBrush->drawTextByLine();



        // Configure the bottom text
        $textBrush->setY($image->height() - $offsetY);
        $textBrush->setVerticalAlign("bottom");

        $textBrush->setWrapTextMaxLines($maxLinesBottomText);

        $textBrush->setText($this->bottomText);

        // Draw bottom text
        $textBrush->drawTextByLine();


        return $this;
    }

    public function getType(): string {
        return "when";
    }
}