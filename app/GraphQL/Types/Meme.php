<?php


namespace App\GraphQL\Types;


abstract class Meme {
    public string $url = "";

    /**
     * Meme constructor.
     * @param array $fill
     */
    public function __construct(array $fill = []) {
        foreach($fill as $field => $value) {
            $this->{$field} = $value;
        }
    }

    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return array
     */
    public function __invoke($_, array $args)
    {
        $res = array_merge($this->__preInvoke($_, $args), [
            "url" => $this->url
        ]);

        return $res;
    }

    abstract public function __preInvoke($_, array $args): array;

}