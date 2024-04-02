<?php 

namespace App\Traits;
use App\Models\Translation;

trait HasTranslation
{
    public function initializeHasTranslation()
    {
        $this->append('translated');
    }

    public function translation()
    {
        return $this->morphOne(Translation::class, 'translatable');
    }

    public function getTranslatedAttribute()
    {
        $lang = app()->getLocale() ?? 'en';
        $trans = $this->translation()->first();
        if (is_null($trans->$lang)) {
            return $trans->en;
        }
        return $trans->$lang;
    }

    public function trans($key)
    {
        return $this->translated[$key];
    }

}