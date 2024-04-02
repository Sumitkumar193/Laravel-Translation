<?php

namespace App\Models;

use App\Traits\HasTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory, HasTranslation;

    protected $fillable = [
        'phone',
        'email',
        'website',
    ];

    public function experience()
    {
        return $this->hasMany(DoctorExp::class);
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'doctor_hospitals');
    }
}
