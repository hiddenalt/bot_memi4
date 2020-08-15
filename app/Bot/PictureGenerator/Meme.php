<?php


namespace App\Bot\PictureGenerator;


use App\TempLink;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\AbstractFont;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

abstract class Meme {

    /**
     * Meme image instance
     * @var Image
     */
    protected Image $image;

    /**
     * Meme constructor.
     * @param Image $image
     */
    public function __construct(Image $image) {
        $this->image = $image; // Setting the template image (size, etc.)
    }


    /**
     * Drawing the meme
     * @return mixed
     */
    abstract public function draw();




    /**
     * Getting the image
     * @return Image
     */
    public function getImage(): Image{
        return $this->image;
    }


    protected string $category = "meme_created";

    /**
     * @return string
     */
    public function getCategory(): string {
        return $this->category;
    }

    /**
     * @param string $category
     * @return Meme
     */
    public function setCategory(string $category): Meme {
        $this->category = $category;
        return $this;
    }

    protected string $influencedBy = "system";

    /**
     * @return string
     */
    public function getInfluencedBy(): string {
        return $this->influencedBy;
    }

    /**
     * @param string $influencedBy
     * @return Meme
     */
    public function setInfluencedBy(string $influencedBy): Meme {
        $this->influencedBy = $influencedBy;
        return $this;
    }

    abstract public function getType(): string;


    protected bool $isPublic = false;

    /**
     * @return bool
     */
    public function isPublic(): bool {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     */
    public function setIsPublic(bool $isPublic): void {
        $this->isPublic = $isPublic;
    }

    protected string $filename;

    /**
     * @return string
     */
    public function getFilename(): string {
        return $this->filename;
    }




    public function makeTempLink(): string {
        $filename = $this->save();

        $this->register();

        do {
            $unique_token = str_random(32);
            $_data = TempLink::query()->where("access_token", $unique_token)->first();
        } while(!empty($_data));


        $path = "storage/" . $this->getCategory() . "/" . $filename;

        $link = new TempLink();

        $link->path = "storage/" . $this->getCategory() . "/" . $filename;
        $link->access_token = $unique_token;

        $link->save();

        return $path . "?access_token=" . $unique_token;
    }

    public function register(){
        $meme = new \App\Meme();

        $meme->category = $this->getCategory();
        $meme->type = $this->getType();
        $meme->is_public = $this->isPublic();
        $meme->filename = $this->getFilename();
        $meme->owner_id = $this->getInfluencedBy();

        $meme->save();
    }

    /**
     * @return string
     */
    public function save(): string {
        $filename = uniqid($this->influencedBy."_".$this->getType()."_", true) . ".jpg";

        $storage = Storage::disk($this->category);
        $storage->put($filename, $this->image->encode("jpg", 90));

        $this->filename = $filename;

        return $filename;
    }

    /**
     * @param Image $image
     * @param string $text
     * @param int $textX
     * @param int $textY
     * @param int $size
     * @param string $text_font
     * @param string $text_color
     * @param string $shadow_color
     * @param string $align
     * @param string $valign
     *
     * TODO: implement in MemeBrush class
     */
    public function drawTextWithShadow(
        Image $image,
        string $text = "generic",
        int $textX = 10,
        int $textY = 10,
        int $size = 40,
        string $text_font = "impact.ttf",
        string $text_color = "#fff",
        string $shadow_color = "#000",
        string $align = "center",
        string $valign = "top"
    ){
        // Shadow with blur
        $textLayer = ImageManagerStatic::canvas( $image->width(), $image->height(), array(0, 0, 0, 0) );

        // Shadow
        for( $x = -2; $x <= 2; $x++ ) {
            for( $y = -2; $y <= 2; $y++ ) {
                $textLayer->text($text, $textX + $x, $textY + $y, function(AbstractFont $font) use($size, $text_font, $shadow_color, $align, $valign) {
                    // TODO: custom font choice
                    $font->file(base_path('resources') . DIRECTORY_SEPARATOR . "fonts" . DIRECTORY_SEPARATOR . $text_font);
                    $font->size($size);
                    $font->color($shadow_color);
                    $font->align($align);
                    $font->valign($valign);
                });
            }
        }
        $textLayer->blur(15);

        // Text
        $textLayer->text($text, $textX, $textY, function (AbstractFont $font) use ($size, $text_font, $text_color, $align, $valign) {
            // TODO: custom font choice
            $font->file(base_path('resources') . DIRECTORY_SEPARATOR . "fonts" . DIRECTORY_SEPARATOR . $text_font);
            $font->size($size);
            $font->color($text_color);
            $font->align($align);
            $font->valign($valign);
        });


        $image->insert($textLayer);
    }


    /**
     * @param Image $image
     * @param string $text
     * @param int $textX
     * @param int $textY
     * @param int $size
     * @param string $text_font
     * @param string $text_color
     * @param string $shadow_color
     * @param string $align
     * @param string $valign
     * @param int $textOffset
     *
     * TODO: implement in MemeBrush class
     */
    public function drawTextWithShadowByLines(
        Image $image,
        string $text = "generic",
        int $textX = 10,
        int $textY = 10,
        int $size = 40,
        string $text_font = "impact.ttf",
        string $text_color = "#fff",
        string $shadow_color = "#000",
        string $align = "center",
        string $valign = "top",
        int $textOffset = 2
    ){
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
                    $valign_offset = -( ( ($linesCount - $i) * $size ) - ($i == 0 ? 0 : $textOffset));
                    break;
            }

            $this->drawTextWithShadow(
                $image,
                $lines[$i],
                $textX,
                $textY + $valign_offset,
                $size,
                $text_font,
                $text_color,
                $shadow_color,
                $align,
                "top"
            );
        }
    }
}