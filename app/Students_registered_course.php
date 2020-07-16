<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Students_registered_course extends Model
{
	public function student(){
		$this->belongsTo(Student::class);
	}
    //
	public function course(){
		$this->belongsTo(Course::class);
	}
	
	public function level(){
		$this->belongsTo(Level::class);
	}

	protected $table = 'students_registered_courses';
}
