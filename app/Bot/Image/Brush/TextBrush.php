<?php


namespace App\Bot\Image\Brush;


use Intervention\Image\AbstractFont;
use Intervention\Image\ImageManagerStatic;

class TextBrush extends Brush {

    protected string $text = "generic";
    protected int $x = 1;
    protected int $y = 1;
    protected int $size = 12;
    protected string $font_file = "impact.ttf";
    protected string $text_color = "#fff";
    protected string $shadow_color = "#000";
    protected string $align = "center";
    protected string $vertical_align = "top";
    protected int $lines_offset = 2;

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getX(): int {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX(int $x): void {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY(): int {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY(int $y): void {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getSize(): int {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getFontFile(): string {
        return $this->font_file;
    }

    /**
     * @param string $font_file
     */
    public function setFontFile(string $font_file): void {
        $this->font_file = $font_file;
    }

    /**
     * @return string
     */
    public function getTextColor(): string {
        return $this->text_color;
    }

    /**
     * @param string $text_color
     */
    public function setTextColor(string $text_color): void {
        $this->text_color = $text_color;
    }

    /**
     * @return string
     */
    public function getShadowColor(): string {
        return $this->shadow_color;
    }

    /**
     * @param string $shadow_color
     */
    public function setShadowColor(string $shadow_color): void {
        $this->shadow_color = $shadow_color;
    }

    /**
     * @return string
     */
    public function getAlign(): string {
        return $this->align;
    }

    /**
     * @param string $align
     */
    public function setAlign(string $align): void {
        $this->align = $align;
    }

    /**
     * @return string
     */
    public function getVerticalAlign(): string {
        return $this->vertical_align;
    }

    /**
     * @param string $vertical_align
     */
    public function setVerticalAlign(string $vertical_align): void {
        $this->vertical_align = $vertical_align;
    }

    /**
     * @return int
     */
    public function getLinesOffset(): int {
        return $this->lines_offset;
    }

    /**
     * @param int $lines_offset
     */
    public function setLinesOffset(int $lines_offset): void {
        $this->lines_offset = $lines_offset;
    }

    /**
     * @return string
     */
    public function getFullTextFontPath(): string{
        return base_path('resources') . DIRECTORY_SEPARATOR . "fonts" . DIRECTORY_SEPARATOR . $this->font_file;
    }

    /**
     * Draw text with shadow on Intervention/Image
     * @return void
     */
    public function drawTextWithShadow(): void{

        $image              = $this->target_image;
        $text               = $this->text;
        $textX              = $this->x;
        $textY              = $this->y;
        $size               = $this->size;
        $full_font_path     = $this->getFullTextFontPath();
        $text_color         = $this->text_color;
        $shadow_color       = $this->shadow_color;
        $align              = $this->align;
        $valign             = $this->vertical_align;

        // Shadow with blur
        $textLayer = ImageManagerStatic::canvas( $image->width(), $image->height(), array(0, 0, 0, 0) );

        // Shadow
        for( $x = -2; $x <= 2; $x++ ) {
            for( $y = -2; $y <= 2; $y++ ) {
                $textLayer->text($text, $textX + $x, $textY + $y, function(AbstractFont $font) use($size, $full_font_path, $shadow_color, $align, $valign) {
                    // TODO: custom font choice
                    $font->file($full_font_path);
                    $font->size($size);
                    $font->color($shadow_color);
                    $font->align($align);
                    $font->valign($valign);
                });
            }
        }
        $textLayer->blur(15);

        // Text
        $textLayer->text($text, $textX, $textY, function (AbstractFont $font) use ($size, $full_font_path, $text_color, $align, $valign) {
            // TODO: custom font choice
            $font->file($full_font_path);
            $font->size($size);
            $font->color($text_color);
            $font->align($align);
            $font->valign($valign);
        });


        $image->insert($textLayer);
    }


    /**
     * Same as drawTextWithShadow() but draws as a dedicated line
     */
    public function drawTextWithShadowByLines(){
        $text               = $this->text;
        $valign             = $this->vertical_align;
        $size               = $this->size;
        $textOffset         = $this->lines_offset;
        $y                  = $this->y;

        $lines = explode("\n", $text);
        $linesCount = count($lines);

        for($i = 0; $i < $linesCount; $i++){

            // Font information
            /*
            // Bad idea, incorrect sizing for meme
            $font = new \Intervention\Image\Gd\Font($lines[$i]);
            $font->file(base_path('resources') . DIRECTORY_SEPARATOR . "fonts" . DIRECTORY_SEPARATOR . $text_font);
            $font->size($size);
            $font->valign('top');
            $font_info = $font->getBoxSize();
            $font_height = $font_info['height'];
            $font_width = $font_info['width'];
            */

            $valign_offset = 0;
            switch($valign){
                case "top":
                default:
                    $valign_offset = ($size * $i) + ($i == 0 ? 0 : $textOffset);
                    break;

                case "middle":
                    // TODO: middle $valign implementation
                    break;

                case "bottom":
                    $valign_offset = -( ( ($linesCount - $i - 1) * $size ) - ($i == 0 ? 0 : $textOffset));
                    break;
            }

            $this->text = $lines[$i];
            $this->y = $y + $valign_offset;
            $this->drawTextWithShadow();
        }
        $this->text = $text;
        $this->y = $y;
    }

}