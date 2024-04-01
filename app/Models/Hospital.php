<?php

namespace App\Models;

use App\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Translation;

class Hospital extends Model
{
    use HasFactory, HasTranslation;

    protected $fillable = [
        'phone',
        'email',
        'website',
    ];

    public function translation()
    {
        return $this->morphOne(Translation::class, 'translatable');
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_hospitals');
    }
}
