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
        return $this->translation()->first()->$lang;
    }

}