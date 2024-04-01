<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\TranslationObserver;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'en',
        'ar',
        'fr',
        'es',
        'de',
        'jp',
    ];

    protected $casts = [
        'en' => 'array',
        'ar' => 'array',
        'fr' => 'array',
        'es' => 'array',
        'de' => 'array',
        'jp' => 'array',
    ];

    public function boot()
    {
        parent::boot();
        Translation::observe(new TranslationObserver);
    }

    public function translatable()
    {
        return $this->morphTo();
    }
}
