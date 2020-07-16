<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Faculty extends Model
{
    //
    public function department()
    {
    	# code...
    	return $this->hasMany(Department::class);
    }

    public $timestamps = false;
    
}
