<?php


namespace App\Bot\Image\Brush;


use Intervention\Image\Image;

class Brush {

    protected Image $target_image;

    /**
     * Brush constructor.
     * @param Image $target_image
     */
    public function __construct(Image $target_image) {
        $this->target_image = $target_image;
    }


    /**
     * @return Image
     */
    public function getTargetImage(): Image {
        return $this->target_image;
    }

    /**
     * @param Image $target_image
     * @return void
     */
    public function setTargetImage(Image &$target_image): void {
        $this->target_image = $target_image;
    }

}