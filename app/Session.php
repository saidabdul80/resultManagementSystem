<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    //
    public function lecturer_allocated_course()
    {
    	$this->has(Lecturer_allocated_course::class);
    }
    public $timestamps = false;
}
