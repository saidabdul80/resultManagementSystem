<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    //
    public function student()
    {
    	$this->belongsTo(Student::class);
    }
    public $timestamps = false;
}
