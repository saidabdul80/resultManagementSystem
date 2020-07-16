<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecturer_allocated_course extends Model
{
	public function course(){
		$this->belongsTo(Course::class);
	}
    //
    public function lecturer(){
		$this->belongsTo(Lecturer::class);
	}

	public function session()
	{
		$this->belongsTo(Session::class);
	}
}
