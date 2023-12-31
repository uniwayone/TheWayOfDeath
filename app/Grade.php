<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    //
    protected $fillable = [
        'schoolId', 'gradeName', 'imgUrl'
    ];
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'gradeId');
    }

    public function children()
    {
        return $this->hasMany(Lesson::class, 'gradeId');
    }

    public function schools()
    {
        return $this->belongsTo(School::class, 'schoolId');
    }
}
