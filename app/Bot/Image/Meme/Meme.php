<?php


namespace App\Bot\Image\Meme;


use App\TempLink;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

abstract class Meme {

    /**
     * Meme canvas instance
     * @var Image
     */
    protected Image $canvas;

    /**
     * Meme constructor.
     * @param Image $image
     */
    public function __construct(Image $image) {
        $this->canvas = $image; // Setting the template image (size, etc.)
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
    public function getCanvas(): Image{
        return $this->canvas;
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
        $storage->put($filename, $this->canvas->encode("jpg", 90));

        $this->filename = $filename;

        return $filename;
    }
}