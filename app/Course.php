<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    public function department(){
    	return $this->belongsTo(Department::class);
    }

    public function students_registered_course(){
        return $this->hasMany(Students_registered_course::class);
    }

    public function lecturer_allocated_course(){
        return $this->hasMany(Lecturer_allocated_course::class);
    }

    protected $fillable = [
    		'course_title',
    		'course_code',
    		'level_id',
    		'credit_unit',
    		'course_description',
    		'department_id',
    		'semester',
    		'status',
    		
    ];
}
