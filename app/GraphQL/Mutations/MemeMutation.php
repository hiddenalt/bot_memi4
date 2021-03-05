<?php


namespace App\GraphQL\Mutations;


use App\Bot\Image\Meme\Meme;

abstract class MemeMutation {

    protected Meme $meme;

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return \App\GraphQL\Types\Meme
     */
    public function __invoke($_, array $args)
    {
        // TODO: response Meme Data
        return $this->prepareMeme($args);
    }

    abstract public function prepareMeme(array $args);

    public function makePublicURLFromPath(string $path){
        return (env('APP_URL') . "/" . $path);
    }
}