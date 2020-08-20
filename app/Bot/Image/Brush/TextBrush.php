<?php


namespace App\Bot\Image\Brush;


use Closure;
use Illuminate\Support\Collection;
use Intervention\Image\AbstractFont;
use Intervention\Image\Gd\Font;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class TextBrush extends Brush {

    protected string $text = "generic";
    protected int $x = 1;
    protected int $y = 1;
    protected int $size = 12;
    protected string $fontFile = "impact.ttf";
    protected string $textColor = "#fff";
    protected string $shadowColor = "#000";
    protected string $align = "center";
    protected string $verticalAlign = "top";
    protected int $linesOffset = 2;
    protected bool $wrapText = false;
    protected int $wrapTextMaxWidth = -1;
    protected int $wrapTextMaxLines = -1;

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
        $this->wrapText();
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
        return $this->fontFile;
    }

    /**
     * @param string $fontFile
     */
    public function setFontFile(string $fontFile): void {
        $this->fontFile = $fontFile;
    }

    /**
     * @return string
     */
    public function getTextColor(): string {
        return $this->textColor;
    }

    /**
     * @param string $textColor
     */
    public function setTextColor(string $textColor): void {
        $this->textColor = $textColor;
    }

    /**
     * @return string
     */
    public function getShadowColor(): string {
        return $this->shadowColor;
    }

    /**
     * @param string $shadowColor
     */
    public function setShadowColor(string $shadowColor): void {
        $this->shadowColor = $shadowColor;
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
        return $this->verticalAlign;
    }

    /**
     * @param string $verticalAlign
     */
    public function setVerticalAlign(string $verticalAlign): void {
        $this->verticalAlign = $verticalAlign;
    }

    /**
     * @return int
     */
    public function getLinesOffset(): int {
        return $this->linesOffset;
    }

    /**
     * @param int $linesOffset
     */
    public function setLinesOffset(int $linesOffset): void {
        $this->linesOffset = $linesOffset;
    }

    /**
     * @return int
     */
    public function getWrapTextMaxWidth(): int {
        return $this->wrapTextMaxWidth;
    }

    /**
     * @param int $wrapTextMaxWidth
     */
    public function setWrapTextMaxWidth(int $wrapTextMaxWidth): void {
        $this->wrapTextMaxWidth = $wrapTextMaxWidth;
    }

    /**
     * @return int
     */
    public function getWrapTextMaxLines(): int {
        return $this->wrapTextMaxLines;
    }

    /**
     * @param int $wrapTextMaxLines
     */
    public function setWrapTextMaxLines(int $wrapTextMaxLines): void {
        $this->wrapTextMaxLines = $wrapTextMaxLines;
    }

    /**
     * @return bool
     */
    public function isWrapText(): bool {
        return $this->wrapText;
    }

    /**
     * @param bool $wrapText
     */
    public function setWrapText(bool $wrapText): void {
        $this->wrapText = $wrapText;
    }

    /**
     * @return string
     */
    public function getFullTextFontPath(): string{
        return base_path('resources') . DIRECTORY_SEPARATOR . "fonts" . DIRECTORY_SEPARATOR . $this->fontFile;
    }

    /**
     * Draw simple text
     * @param Image|null $layer
     */

    public function drawText(Image $layer = null): void{
        $text               = $this->text;
        $textX              = $this->x;
        $textY              = $this->y;
        $size               = $this->size;
        $fullFontPath       = $this->getFullTextFontPath();
        $textColor          = $this->textColor;
        $align              = $this->align;
        $valign             = $this->verticalAlign;

        if($layer == null) $layer = $this->target_image;

        $layer->text($text, $textX, $textY, function(AbstractFont $font) use($size, $fullFontPath, $textColor, $align, $valign) {
            // TODO: custom font choice
            $font->file($fullFontPath);
            $font->size($size);
            $font->color($textColor);
            $font->align($align);
            $font->valign($valign);
        });

//        $layer->rectangle($textX - 10, $textY, $textX + 10, $textY + 1, function ($draw) {
//            $draw->border(1, '#79b8ff');
//        });
    }

    /**
     * Draw text with shadow
     * @param Image|null $textLayer
     * @return void
     */
    public function drawTextWithShadow(Image $textLayer = null): void{

        $image              = $this->target_image;
        $textX              = $this->x;
        $textY              = $this->y;
        $textColor          = $this->textColor;
        $shadowColor        = $this->shadowColor;

        // Shadow layer
        if($textLayer == null)
            $textLayer = ImageManagerStatic::canvas( $image->width(), $image->height(), array(0, 0, 0, 0) );

        // Shadow
        $this->setTextColor($shadowColor);
        for( $x = -2; $x <= 2; $x++ ) {
            for( $y = -2; $y <= 2; $y++ ) {
                $this->x = $textX + $x;
                $this->y = $textY + $y;
                $this->drawText($textLayer);
            }
        }

        // Blur the layer
        // $textLayer->blur(15); // freezes the code

        $this->x = $textX;
        $this->y = $textY;
        $this->textColor = $textColor;

        // Text
        $this->drawText($textLayer);

        $image->insert($textLayer);
    }


    /**
     * Same as drawTextWithShadow() but draws each line as a dedicated one
     * @param Image|null $layer
     */
    public function drawTextWithShadowByLine(Image $layer = null){
        $this->byLine(function(int $index) use($layer){
            $this->drawTextWithShadow($layer);
        });
    }

    /**
     * Same as drawText() but draws each line as a dedicated one
     * @param Image|null $layer
     */
    public function drawTextByLine(Image $layer = null){
        $this->byLine(function(int $index) use($layer){
            $this->drawText($layer);
        });
    }

    /**
     * Do the stuff by line
     * @param callable $callback
     * TODO: optimization (?)
     */
    public function byLine(callable $callback){
        $text               = $this->text;
        $valign             = $this->verticalAlign;
        $size               = $this->size;
        $textOffset         = $this->linesOffset;
        $y                  = $this->y;

        $lines = explode("\n", $text);
        $linesCount = count($lines);

        // TODO: verticalAlign rebase & optimization
        $_ = $this->verticalAlign;
        $this->verticalAlign = "center";

        for($i = 0; $i < $linesCount; $i++){
            $valign_offset = 0;
            switch($valign){
                case "top":
                default:
                    $valign_offset = ($size * $i) + $textOffset;
                    break;

                case "middle":
                    // TODO: middle $valign implementation
                    break;

                case "bottom":
                    $valign_offset = -(($linesCount - $i - 1) * $size) - $textOffset;
                    break;
            }

            $this->text = $lines[$i];
            $this->y = $y + $valign_offset;

            // Call the task and bind `$this` to current TextBrush
            Closure::bind($callback, $this)($i);
        }
        $this->text = $text;
        $this->y = $y;
        $this->verticalAlign = $_;
    }

    /**
     * Font box size for calculating the positions
     * @return array;
     */
    public function getFontBoxSize(){
        $font = new Font($this->text);
        $font->file($this->getFullTextFontPath());
        $font->size($this->size);
        $font->valign('top');

        return (array) $font->getBoxSize();
    }

    /**
     * Get height of text rendered with drawTextWithShadowByLines()-like methods
     * TODO: optimization (?)
     * @return int
     */
    public function getHeightByLine(){
        $height = 0;
        $textOffset = $this->linesOffset;

        $this->byLine(function(int $i) use(&$height, $textOffset){
            $font_info = $this->getFontBoxSize();
            $font_height = $font_info['height'];
            $height += $font_height + $textOffset;
        });

        return $height;
    }

    /**
     * Wrapping the text with max $width
     * Source: https://github.com/Intervention/image/issues/143#issuecomment-492592752
     * TODO: optimization with Collection
     * TODO: ellipsis if text sliced
     * TODO: characters limit (~512 (?))
     */
    protected function wrapText() {

        if(!$this->wrapText) return;

        $width = $this->wrapTextMaxWidth;
        $maxLines = $this->wrapTextMaxLines;

        if($width <= 0)
            $width = $this->target_image->getWidth();

        $size = $this->size;
        $path = $this->fontFile;
        $text = $this->text;

        //-- Helpers
        $line = [];
        $lines = [];
        //-- Loop through words
        foreach(explode(" ", $text) as $word) {
            //-- Add to line
            $line[] = $word;

            //-- Create new text query
            $testBrush = new TextBrush($this->target_image);
            $testBrush->setText(implode(" ", $line));
            $testBrush->setFontFile($path);
            $testBrush->setSize($size);
            $info = $testBrush->getFontBoxSize();

            //-- Check within bounds
            if ($info['width'] >= $width) {
                //-- We have gone to far!
                array_pop($line);
                $lines[] = implode(" ", $line);
                //-- Start new line
                unset($line);
                $line[] = $word;
            }
        }

        //-- We are at the end of the string
        $lines[] = implode(" ", $line);
        $lines = implode("\n", $lines);

        if($maxLines > 0)
            $lines = implode("\n", (new Collection(explode("\n", $lines)))->slice(0, $maxLines)->all());



        $this->text = $lines;
    }

}