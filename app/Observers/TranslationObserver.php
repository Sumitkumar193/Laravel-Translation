<?php

namespace App\Observers;

use App\Models\Translation;
use App\Services\TranslationService;

class TranslationObserver
{
    public function created(Translation $translation)
    {
        TranslationService::translateModel($translation);
    }
}
