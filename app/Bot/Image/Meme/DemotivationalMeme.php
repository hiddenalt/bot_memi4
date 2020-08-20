<?php


namespace App\Bot\Image\Meme;


use App\Bot\Image\Brush\TextBrush;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class DemotivationalMeme extends Meme{

    /**
     * @var Image
     */
    protected Image $baseImage;
    /**
     * @var string
     */
    protected string $title = "Title";
    /**
     * @var string
     */
    protected string $subtitle = "Subtitle";

    /**
     * WhenMeme constructor.
     */
    public function __construct() {
        parent::__construct(ImageManagerStatic::canvas(1024, 1024, '#000'));
    }

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
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $title
     * @return DemotivationalMeme
     */
    public function setTitle(string $title): DemotivationalMeme {
        $this->title = $title;
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

        $image = $this->image;
        $imageWidth = $image->getWidth();
        $imageHeight = $image->getHeight();

        // TODO: customization
        $borderSize                   = 5;                              // Border size in px
        $margin                       = 50;                             // Edges offset
        $padding                      = 7;                              // Offset between border and image
        $borderBottomOffset           = 40;                             // Border bottom offset
        $titleBottomPadding           = 25;                             // Title bottom padding
        $lineBottomPadding            = 20;                             // Each line bottom padding (title & subtitle)
        $textWrapMaxWidth             = $imageWidth - ($margin * 2);    // Max width for wrapping the texts (title & subtitle)

        $maxTitleLines                = 3;                              // Max value of lines in title
        $maxSubTitleLines             = 5;                              // Max value of lines in subtitle

        $bottomOffset = 0;


        $textBrush = new TextBrush($image);

        /* Configure & draw subtitle */
        $textBrush->setSize(24);                            // TODO: custom subtitle font size
        $textBrush->setFontFile("times_new_roman.ttf");  // TODO: custom subtitle font file
        $textBrush->setTextColor("#fff");               // TODO: custom subtitle font color
        $textBrush->setAlign("center");
        $textBrush->setVerticalAlign("bottom");
        $textBrush->setLinesOffset($lineBottomPadding);
        $textBrush->setX($imageWidth / 2);
        $textBrush->setY($imageHeight - $margin);

        // Wrapping for title
        $textBrush->setWrapText(true);
        $textBrush->setWrapTextMaxLines($maxSubTitleLines);
        $textBrush->setWrapTextMaxWidth($textWrapMaxWidth);


        $textBrush->setText($this->subtitle);
        $bottomOffset += $textBrush->getHeightByLine() + $titleBottomPadding;   // TODO: extra space fix
        $textBrush->drawTextByLine();



        /* Configure & draw title */

        // Wrapping for title
        $textBrush->setWrapTextMaxLines($maxTitleLines);


        $textBrush->setSize(72);                            // TODO: custom title font size
        // $textBrush->setFontFile("arial.ttf");                // TODO: custom title font file
        // $textBrush->setTextColor("#fff");                    // TODO: custom title font color
        $textBrush->setY($imageHeight - $margin - $bottomOffset);


        $textBrush->setText($this->title);
        $bottomOffset += $textBrush->getHeightByLine() + $borderBottomOffset;   // TODO: extra space fix
        $textBrush->drawTextByLine();




        // Border lines of poster
        $image->rectangle($margin, $margin, $imageWidth - $margin, $imageHeight - $margin - $bottomOffset, function ($draw) use($borderSize) {
            // TODO: custom border colors
            // TODO: custom border width
            $draw->border($borderSize, '#fff');
        });

        // Poster image
        $w = $image->width()  - ($margin + $borderSize + $padding) * 2;
        $h = $image->height() - ($margin + $borderSize + $padding) * 2 - $bottomOffset;
        $x = $margin + $borderSize + $padding;
        $y = $x;

        $this->baseImage->resize($w, $h);
        $image->insert($this->baseImage, 'top-left', $x, $y);

        return $this;
    }

    public function getType(): string {
        return "demotivational";
    }
}