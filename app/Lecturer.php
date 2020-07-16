<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Lecturer extends Model
{
    public function department()
    {
    	return $this->belongsTo(Department::class);
    }

     public function lecturer_allocated_course()
    {
    	return $this->hasMany(Lecturer_allocated_course::class);
    }
    
    public $timestamps = false;
}
