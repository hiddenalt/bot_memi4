<?php


namespace App\GraphQL\Types;


class WhenMeme extends Meme {

    public string $top_text = "";
    public string $bottom_text = "";

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return array
     */
    public function __preInvoke($_, array $args): array
    {
        return [
            "top_text" => $this->top_text,
            "bottom_text" => $this->bottom_text,
        ];
    }

}