<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class compiled_r extends Model
{
    // 
    protected $table = 'compiled_r';
	
    use LogsActivity;

    protected static $logAttributes = ['session', 'semester', 'level', 'department', 'created_by', 'created_on'];

    public $timestamps = false;
}
