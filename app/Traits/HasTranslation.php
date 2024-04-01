<?php 

namespace App\Traits;

trait HasTranslation
{
    public function initializeHasTranslation()
    {
        $this->append('translated');
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

}