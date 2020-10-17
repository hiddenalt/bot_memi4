<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class Language {
    const DEFAULT_LOCALE = "en";
    const LOCALES_LIST_OPTION = "app.locales";

    /**
     * @param bool $onlyCodes
     * @return array
     */
    public function getAvailableLanguages($onlyCodes = false){
        $available = [];
        foreach (config(self::LOCALES_LIST_OPTION) as $locale => $label){
            if($this->isAvailable($locale)){
                if($onlyCodes){
                    $available[] = $locale;
                    continue;
                }
                $available[$locale] = $label;
            }
        }
        return $available;
    }

    /**
     * Check if locale is available in system (both config property and file exist)
     * @param string $langCode
     * @return bool
     */
    public function isAvailable(string $langCode){
        return
            Arr::has(config(self::LOCALES_LIST_OPTION), $langCode) &&
            File::exists(app()->resourcePath("lang/" . $langCode . ".json"));
    }

    /**
     * Check the locale & set as an application one.
     * @param string $langCode
     * @return bool
     */
    public function setLocale(string $langCode){
        if(!$this->isAvailable($langCode))
            return false;

        app()->setLocale($langCode);
        return true;
    }
}
