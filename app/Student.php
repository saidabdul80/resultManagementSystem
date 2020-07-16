<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    public function department()
    {
    	# code...
    	return $this->belongsTo(Department::class);
    }

    public function students_registered_course(){
    	return $this->hasMany(Students_registered_course::class);
    }

    public function result(){
    	return $this->hasMany(Result::class);
    }

   protected $fillable = ['other_name'];

    public $timestamps = false;
}
