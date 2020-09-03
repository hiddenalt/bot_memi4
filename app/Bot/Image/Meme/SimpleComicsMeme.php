<?php


namespace App\Bot\Image\Meme;


use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class SimpleComicsMeme extends Meme {

    /**
     * @var Image
     */
    protected Image $frameImage1;
    /**
     * @var Image
     */
    protected Image $frameImage2;
    /**
     * @var Image
     */
    protected Image $frameImage3;
    /**
     * @var Image
     */
    protected Image $frameImage4;

    /**
     * @var string
     */
    protected string $frameLabel1;
    /**
     * @var string
     */
    protected string $frameLabel2;
    /**
     * @var string
     */
    protected string $frameLabel3;
    /**
     * @var string
     */
    protected string $frameLabel4;

    /**
     * SimpleComicsMeme constructor.
     */
    public function __construct() {
        parent::__construct(ImageManagerStatic::canvas(1024, 1024, '#fff'));
    }

    /**
     * @return Image
     */
    public function getFrameImage1(): Image {
        return $this->frameImage1;
    }

    /**
     * @param Image $frameImage1
     * @return SimpleComicsMeme
     */
    public function setFrameImage1(Image $frameImage1): SimpleComicsMeme {
        $this->frameImage1 = $frameImage1;
        return $this;
    }

    /**
     * @return Image
     */
    public function getFrameImage2(): Image {
        return $this->frameImage2;
    }

    /**
     * @param Image $frameImage2
     * @return SimpleComicsMeme
     */
    public function setFrameImage2(Image $frameImage2): SimpleComicsMeme {
        $this->frameImage2 = $frameImage2;
        return $this;
    }

    /**
     * @return Image
     */
    public function getFrameImage3(): Image {
        return $this->frameImage3;
    }

    /**
     * @param Image $frameImage3
     * @return SimpleComicsMeme
     */
    public function setFrameImage3(Image $frameImage3): SimpleComicsMeme {
        $this->frameImage3 = $frameImage3;
        return $this;
    }

    /**
     * @return Image
     */
    public function getFrameImage4(): Image {
        return $this->frameImage4;
    }

    /**
     * @param Image $frameImage4
     * @return SimpleComicsMeme
     */
    public function setFrameImage4(Image $frameImage4): SimpleComicsMeme {
        $this->frameImage4 = $frameImage4;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrameLabel1(): string {
        return $this->frameLabel1;
    }

    /**
     * @param string $frameLabel1
     * @return SimpleComicsMeme
     */
    public function setFrameLabel1(string $frameLabel1): SimpleComicsMeme {
        $this->frameLabel1 = $frameLabel1;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrameLabel2(): string {
        return $this->frameLabel2;
    }

    /**
     * @param string $frameLabel2
     * @return SimpleComicsMeme
     */
    public function setFrameLabel2(string $frameLabel2): SimpleComicsMeme {
        $this->frameLabel2 = $frameLabel2;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrameLabel3(): string {
        return $this->frameLabel3;
    }

    /**
     * @param string $frameLabel3
     * @return SimpleComicsMeme
     */
    public function setFrameLabel3(string $frameLabel3): SimpleComicsMeme {
        $this->frameLabel3 = $frameLabel3;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrameLabel4(): string {
        return $this->frameLabel4;
    }

    /**
     * @param string $frameLabel4
     * @return SimpleComicsMeme
     */
    public function setFrameLabel4(string $frameLabel4): SimpleComicsMeme {
        $this->frameLabel4 = $frameLabel4;
        return $this;
    }




    /**
     * Drawing the meme
     * @return SimpleComicsMeme
     */
    public function draw() {

        $image = $this->canvas;
        $width = $image->getWidth();
        $height = $image->getHeight();
        $linesSize = 6;

        // TODO: custom border color
        // TODO: custom text shadow color
        // TODO: custom text size
        // TODO: custom text font file
        // TODO: custom lines size

        // Image 1
        $frame = $this->frameImage1;
        $frame->resize($width / 2, $height / 2);
        $image->insert($frame, 'top-left', 0, 0);

        // Image 2
        $frame = $this->frameImage2;
        $frame->resize($width / 2, $height / 2);
        $image->insert($frame, 'top-left', $width / 2, 0);

        // Image 3
        $frame = $this->frameImage3;
        $frame->resize($width / 2, $height / 2);
        $image->insert($frame, 'top-left', 0, $height / 2);

        // Image 4
        $frame = $this->frameImage4;
        $frame->resize($width / 2, $height / 2);
        $image->insert($frame, 'top-left', $width / 2, $height / 2);

        // TODO: labels rendering


        // First line
        $image->line(($width / 2) - $linesSize, 0, ($width / 2) - $linesSize, $height, function ($draw) {
            $draw->color('#000');
        });

        // Second line
        $image->line(0, ($height / 2) - $linesSize, $width, ($height / 2) + $linesSize, function ($draw) {
            $draw->color('#000');
        });

//        $textBrush = new TextBrush($image);

        return $this;
    }

    public function getType(): string {
        return "comics";
    }
}