<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{   

    public function Permission()
    {   
    	$Admin_Permission = Permission::where('permission_code','create-edit-users')->first();
		$Lecturer_Permission = Permission::where('permission_code', 'input-result')->first();
		$Examiner_Permission = Permission::where('permission_code', 'process-result')->first();
		$Access_Time_Permission = Permission::where('permission_code', 'access-timing-result')->first();
		$Access_Result_Permission = Permission::where('permission_code', 'access-result')->first();
		$User_Permission = Permission::where('permission_code', 'no-permission')->first();
		//RoleTableSeeder.php
		$Admin_Role = new Role();
		$Admin_Role->role_code = 'create-edit-user';
		$Admin_Role->role = 'Admin';
		$Admin_Role->save();
		$Admin_Role->permissions()->attach($Admin_Permission);

		$Lecturer_Role = new Role();
		$Lecturer_Role->role_code = 'lecturer';
		$Lecturer_Role->role = 'Lecturer';
		$Lecturer_Role->save();
		$Lecturer_Role->permissions()->attach($Lecturer_Permission);

		$Examiner_Role = new Role();
		$Examiner_Role->role_code = 'Dept-Examiner';
		$Examiner_Role->role = 'Examiner';
		$Examiner_Role->save();
		$Examiner_Role->permissions()->attach($Examiner_Permission);

		$Faculty_Role = new Role();
		$Faculty_Role->role_code = 'Faculty-Examiner';
		$Faculty_Role->role = 'Faculty';
		$Faculty_Role->save();
		$Faculty_Role->permissions()->attach($Access_Time_Permission);

		$Senate1_Role = new Role();
		$Senate1_Role->role_code = 'sanate';
		$Senate1_Role->role = 'Senate1';
		$Senate1_Role->save();
		$Senate1_Role->permissions()->attach($Access_Result_Permission);

		$Senate2_Role = new Role();
		$Senate2_Role->role_code = 'theSenate';
		$Senate2_Role->role = 'Senate2';
		$Senate2_Role->save();
		$Senate2_Role->permissions()->attach($Access_Result_Permission);

		$User_Role = new Role();
		$User_Role->role_code = 'idleuser';
		$User_Role->role = 'user';
		$User_Role->save();
		$User_Role->permissions()->attach($User_Permission);



		$Admin_Role = Role::where('role','Admin')->first();
		$Lecturer_Role = Role::where('role', 'Lecturer')->first();
		$Examiner_Role = Role::where('role', 'Examiner')->first();
		$Faculty_Role = Role::where('role', 'Faculty')->first();
		$Senate1_Role = Role::where('role', 'Senate1')->first();
		$Senate2_Role = Role::where('role', 'Senate2')->first();
		$User = Role::where('role', 'User')->first();

		$Admin_Permission = new Permission();
		$Admin_Permission->permission_code = 'create-edit-users';
		$Admin_Permission->permission = 'create edit users';
		$Admin_Permission->save();
		$Admin_Permission->roles()->attach($Admin_Role);

		$Lecturer_Permission = new Permission();
		$Lecturer_Permission->permission_code = 'input-result';
		$Lecturer_Permission->permission = 'input result';
		$Lecturer_Permission->save();
		$Lecturer_Permission->roles()->attach($Lecturer_Role);

		$Examiner_Permission = new Permission();
		$Examiner_Permission->permission_code = 'process-result';
		$Examiner_Permission->permission = 'process result';
		$Examiner_Permission->save();
		$Examiner_Permission->roles()->attach($Examiner_Role);

		$Access_Time_Permission = new Permission();
		$Access_Time_Permission->permission_code = 'access-timing-result';
		$Access_Time_Permission->permission = 'access timing result';
		$Access_Time_Permission->save();
		$Access_Time_Permission->roles()->attach($Faculty_Role);

		$Access_Result_Permission = new Permission();
		$Access_Result_Permission->permission_code = 'access-result';
		$Access_Result_Permission->permission = 'access result';
		$Access_Result_Permission->save();
		$Access_Result_Permission->roles()->attach($Senate1_Role);

		$Access_Result_Permission = new Permission();
		$Access_Result_Permission->permission_code = 'access-result';
		$Access_Result_Permission->permission = 'access result';
		$Access_Result_Permission->save();
		$Access_Result_Permission->roles()->attach($Senate2_Role);

		$User_Permission = new Permission();
		$User_Permission->permission_code = 'no-permission';
		$User_Permission->permission = 'no permission';
		$User_Permission->save();
		$User_Permission->roles()->attach($User_Role);

/*
		$Admin = Role::where('role_code','developer')->first();
		$manager_role = Role::where('role_code', 'manager')->first();
		$dev_perm = Permission::where('role_code','create-tasks')->first();
		$manager_perm = Permission::where('role_code','edit-users')->first();
*/
		$admin = new User();
		$admin->name = 'Said Abdulsalam';
		$admin->email = 'saidabdulsalam05@gmail.com';
		$admin->password = bcrypt('08096642065');
		$admin->role_id = '';
		$admin->save();
		$admin->roles()->attach($Admin_Role);
		$admin->permissions()->attach($Admin_Permission);
		
		return redirect()->back();
    }
}

