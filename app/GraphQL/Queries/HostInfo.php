<?php

namespace App\GraphQL\Queries;

class HostInfo
{
    /**
     * @param null $_
     * @param array<string, mixed> $args
     * @return array
     */
    public function __invoke($_, array $args)
    {
        return [
            "name" => env("APP_NAME") ?? "?",
            "image" => env("APP_URL") . "/logo.png",
            "version" => app('pragmarx.version')->format("full") ?? "?",
            "phpVersion" => phpversion() ?? "?",

            "links" => [
                "wiki" => env("WELCOME_PAGE_WIKI") ?? "",
                "github" => env("WELCOME_PAGE_GITHUB_URL") ?? "",
                "vk" => env("WELCOME_PAGE_VK_URL") ?? "",
                "telegram" => env("WELCOME_PAGE_TELEGRAM_URL") ?? "",
            ]
        ];
    }
}
