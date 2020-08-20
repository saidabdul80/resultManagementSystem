<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Spatie\Activitylog\Traits\LogsActivity;

class Grade extends Model
{
	 
	use LogsActivity;

    protected $fillable = ['*'];

    protected static $logAttributes = ['name', 'created_by', 'created_on'];
    public $timestamps = false;
    //
}
