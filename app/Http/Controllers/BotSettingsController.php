<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BotSettingsController extends Controller
{
    private static array $settings_types = [
        // TODO: translate to English

        "font" => [
            "title" => "Шрифт текста",
            "description" => "Шрифт текста генерируемых мемов (только для этого чата)."
        ],

        "text_color" => [
            "title" => "Цвет текста",
            "description" => "Цвет текста генерируемых мемов (только для этого чата)."
        ],

        "text_shadow_color" => [
            "title" => "Цвет тени текста",
            "description" => "Цвет тени текста генерируемых мемов (только для этого чата)."
        ],

        "demotivation_bg_color" => [
            "title" => "Цвет фона демотиватора",
            "description" => "Цвет фона мема-демотиватора (только для этого чата)."
        ],

        "demotivation_border_color" => [
            "title" => "Цвет рамки демотиватора",
            "description" => "Цвет рамки мема-демотиватора (только для этого чата)."
        ],

        "show_author" => [
            "title" => "Показывать инициатора генерации",
            "description" => "Показывать никнейм/ID пользователя (и платформу) этого чата, способствовавшего генерации мема."
        ]
    ];

    /**
     * @return array
     */
    public static function getSettingsTypesInfo(): array {
        return self::$settings_types;
    }

    /**
     * @return array
     */
    public static function getSettingsTypes(): array {
        return array_keys(self::$settings_types);
    }


}
