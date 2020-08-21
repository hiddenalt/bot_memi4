<?php

namespace App\Http\Controllers;


class BotSettingsController extends Controller
{

    /**
     * @return array
     */
    public static function getSettingsTypesInfo(): array {
        return [
            "font" => [
                "title" => __("settings-types.font.title"),
                "description" => __("settings-types.font.description")
            ],

            "text_color" => [
                "title" => __("settings-types.color.title"),
                "description" => __("settings-types.color.description"),
            ],

            "text_shadow_color" => [
                "title" => __("settings-types.shadow.color.title"),
                "description" => __("settings-types.shadow.color.description"),
            ],

            "demotivational_bg_color" => [
                "title" => __("settings-types.demotivational.bg.color.title"),
                "description" => __("settings-types.demotivational.bg.color.description"),
            ],

            "demotivational_border_color" => [
                "title" => __("settings-types.demotivational.border.color.title"),
                "description" => __("settings-types.demotivational.border.color.description"),
            ],

            "show_author" => [
                "title" => __("settings-types.show-author.title"),
                "description" => __("settings-types.show-author.description"),
            ]
        ];
    }

    /**
     * @return array
     */
    public static function getSettingsTypes(): array {
        return array_keys(self::getSettingsTypesInfo());
    }


}
