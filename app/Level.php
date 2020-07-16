<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
     public function students_registered_course(){
    	return $this->hasMany(Students_registered_course::class);
    }
}
