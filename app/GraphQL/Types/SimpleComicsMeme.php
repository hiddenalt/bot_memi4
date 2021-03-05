<?php


namespace App\GraphQL\Types;


class SimpleComicsMeme extends Meme {
    public string $label1 = "";
    public string $label2 = "";
    public string $label3 = "";
    public string $label4 = "";

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return array
     */
    public function __preInvoke($_, array $args): array
    {
        return [
            "label1" => $this->label1,
            "label2" => $this->label2,
            "label3" => $this->label3,
            "label4" => $this->label4
        ];
    }
}