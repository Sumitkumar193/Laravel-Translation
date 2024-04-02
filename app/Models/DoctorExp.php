<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslation;

class DoctorExp extends Model
{
    use HasFactory, HasTranslation;

    protected $fillable = [
        'hospital_name',
        'designation',
        'from',
        'to',
        'doctor_id',
    ];

}
