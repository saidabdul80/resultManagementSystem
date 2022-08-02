<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Traits\LogsActivity;
/*use \App\Lecturer;
use \App\Department;
use \App\Faculty;*/
use \App\User;
use \App\Role;
class AssignroleController extends Controller
{
    //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('admin/assign_role');
    }

    public function Edit(Request $request)
    {
    	$rid = $request->input('rid');
		$uid = $request->input('uid');
		$type = $request->input('type');
			//update user role
			User::where('id',$uid)->update(['role_id'=> $rid]);
			return response()->json(['success'=>200]);
    }
    
    /*public function removeRole(Request $request)
    {
    	$rid = $request->input('rid');
		$uid = $request->input('uid');
		//$nfrole = '5'.','.$rid;
		User::where('email',$uid)->update(['role_id'=> $rid]);
		return response()->json(['success'=>200]);
	}*/
}
