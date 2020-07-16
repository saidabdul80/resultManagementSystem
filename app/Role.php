<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
	use LogsActivity;

    public function permissions()
    {
    	return $this->belongsToMany(Permission::class,'roles_permissions');
    }

    public function users()
    {
    	return $this->belongsToMany(User::class, 'users_role');
    }

}
