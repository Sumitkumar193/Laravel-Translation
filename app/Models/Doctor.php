<?php

namespace App\Models;

use App\Traits\HasTranslation;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory, HasTranslation;

    protected $appends = [
        'overall_experience',
    ];

    protected $fillable = [
        'phone',
        'email',
        'website',
    ];

    public function experience()
    {
        return $this->hasMany(DoctorExp::class);
    }

    public function getOverallExperienceAttribute()
    {
        $experience = $this->experience->sortBy('from');
        $totalExperience = [];
        foreach ($experience as $exp) {
            $expFrom = Carbon::parse($exp->from);
            $expTo = Carbon::parse($exp->to);
            for ($date = $expFrom; $date->lte($expTo); $date->addMonth()) {
                $totalExperience[$date->format('F, Y')] = true;
            }
        }
        return count($totalExperience);
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'doctor_hospitals');
    }
}
