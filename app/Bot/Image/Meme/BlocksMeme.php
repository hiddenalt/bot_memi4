<?php


namespace App\Bot\Image\Meme;


use Intervention\Image\Image;

class BlocksMeme extends Meme {

    /**
     * @var Image
     */
    protected Image $blockImage1;
    /**
     * @var Image
     */
    protected Image $blockImage2;
    /**
     * @var Image
     */
    protected Image $blockImage3;
    /**
     * @var Image
     */
    protected Image $blockImage4;

    /**
     * @var string
     */
    protected string $blockTitle1;
    /**
     * @var string
     */
    protected string $blockTitle2;
    /**
     * @var string
     */
    protected string $blockTitle3;
    /**
     * @var string
     */
    protected string $blockTitle4;

    /**
     * @return Image
     */
    public function getBlockImage1(): Image {
        return $this->blockImage1;
    }

    /**
     * @param Image $blockImage1
     * @return BlocksMeme
     */
    public function setBlockImage1(Image $blockImage1): BlocksMeme {
        $this->blockImage1 = $blockImage1;
        return $this;
    }

    /**
     * @return Image
     */
    public function getBlockImage2(): Image {
        return $this->blockImage2;
    }

    /**
     * @param Image $blockImage2
     * @return BlocksMeme
     */
    public function setBlockImage2(Image $blockImage2): BlocksMeme {
        $this->blockImage2 = $blockImage2;
        return $this;
    }

    /**
     * @return Image
     */
    public function getBlockImage3(): Image {
        return $this->blockImage3;
    }

    /**
     * @param Image $blockImage3
     * @return BlocksMeme
     */
    public function setBlockImage3(Image $blockImage3): BlocksMeme {
        $this->blockImage3 = $blockImage3;
        return $this;
    }

    /**
     * @return Image
     */
    public function getBlockImage4(): Image {
        return $this->blockImage4;
    }

    /**
     * @param Image $blockImage4
     * @return BlocksMeme
     */
    public function setBlockImage4(Image $blockImage4): BlocksMeme {
        $this->blockImage4 = $blockImage4;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockTitle1(): string {
        return $this->blockTitle1;
    }

    /**
     * @param string $blockTitle1
     * @return BlocksMeme
     */
    public function setBlockTitle1(string $blockTitle1): BlocksMeme {
        $this->blockTitle1 = $blockTitle1;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockTitle2(): string {
        return $this->blockTitle2;
    }

    /**
     * @param string $blockTitle2
     * @return BlocksMeme
     */
    public function setBlockTitle2(string $blockTitle2): BlocksMeme {
        $this->blockTitle2 = $blockTitle2;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockTitle3(): string {
        return $this->blockTitle3;
    }

    /**
     * @param string $blockTitle3
     * @return BlocksMeme
     */
    public function setBlockTitle3(string $blockTitle3): BlocksMeme {
        $this->blockTitle3 = $blockTitle3;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockTitle4(): string {
        return $this->blockTitle4;
    }

    /**
     * @param string $blockTitle4
     * @return BlocksMeme
     */
    public function setBlockTitle4(string $blockTitle4): BlocksMeme {
        $this->blockTitle4 = $blockTitle4;
        return $this;
    }




    /**
     * Drawing the meme
     * @return BlocksMeme
     */
    public function draw() {
        // TODO: Implement draw() method.
        return $this;
    }

    public function getType(): string {
        return "blocks";
    }
}