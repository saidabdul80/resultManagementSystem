<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Spatie\Activitylog\Model\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends Model
{
    //
    use LogsActivity;
    public function faculty()
    {
    	return $this->belongsTo(Faculty::class);
    }

    public function course(){
    	return $this->hasMany(Course::class);
    }
    public function student()
    {
        return $this->hasMany(Student::class);
    }
     public function lecturer()
     {
         return $this->hasOne(Lecturer::class);
     }

    protected $fillable = ['department', 'department_abbr', 'faculty_id'];
    protected static $logsAttributes = ['*'];
    public $timestamps = false;
    
}
