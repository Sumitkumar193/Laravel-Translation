<?php

namespace App\Services;

use App\Models\Translation;
use Google\Cloud\Translate\V2\TranslateClient;

class TranslationService
{
    private static $translateClient;
    private static $languagesArray = ['ar', 'fr', 'es', 'de', 'jp'];

    public static function translate($text, $target)
    {
        if (!self::$translateClient) {
            self::$translateClient = new TranslateClient([
                'key' => env('GOOGLE_TRANSLATE_API_KEY'),
            ]);
        }

        $result = self::$translateClient->translate($text, [
            'target' => $target,
        ]);

        return $result['text'];
    }

    public static function detectLanguage($text)
    {
        if (!self::$translateClient) {
            self::$translateClient = new TranslateClient([
                'key' => env('GOOGLE_TRANSLATE_API_KEY'),
            ]);
        }

        $result = self::$translateClient->detectLanguage($text);

        return $result['language'];
    }

    public static function translateModel(Translation $translation)
    {
        $en = $translation->en;
        $keys = array_keys($en);
        $values = array_values($en);

        foreach (self::$languagesArray as $language) {
            $translatedText = self::translate(json_encode($values), $language);
            $translatedValues = json_decode($translatedText, true);
            $translation->$language = array_combine($keys, $translatedValues);
            $translation->save();
        }

        return $translation;
    }
}