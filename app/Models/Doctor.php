<?php

namespace App\Models;

use App\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Translation;

class Doctor extends Model
{
    use HasFactory, HasTranslation;

    public function translation()
    {
        return $this->morphOne(Translation::class, 'translatable');
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'doctor_hospitals');
    }
}
