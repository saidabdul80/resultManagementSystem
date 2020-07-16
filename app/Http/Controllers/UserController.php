<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function RoleCheck($role_ids, $user_id)
    {
    	//return 1;
    	$role_ids = array_map('intval', explode(',', $role_ids )) ;
        //fetch all role from Roles table with all user role_iid
    	$userRolesData = Role::whereIn('id',$role_ids)->get();
    	
      // return dd($userRolesData);
    	//$userRoles = array();
    	foreach ($userRolesData as $role) {
    		$userRoles[] = $role->role; 
    	}

    	//user role on Login
    	$YearSession = \DB::table('sessions')->where('c_set','=', '1')->get();
    	$username = explode('@',Auth::user()->email);
    	session(['userRoles'=> $userRoles]);
    	session(['YearSession'=> $YearSession[0]->session]);
    	session(['username'=> $username[0]]);

    	
    	
    	//check if user is Acadamic User
    	//$email = \App\User::find($user_id)->first()->email;
    	
    	$lecturerEmail = \App\Lecturer::where('email',Auth::user()->email)->first();
            
    	if(is_null($lecturerEmail)){

    		//send not allow to academic section for users like lecturere, examiner fields
    		session('AccessPermission', 0);
    		if(in_array('admin', $userRoles)){
    			//redirect to admin dashboard. does not matter if has other below rows
    			return redirect('/admin')->with('status','You have successfully loged in');
    		}else if(in_array('senateB', $userRoles)){
    			//redirect to senateB dashboard. does not matter if has other below rows

    		}else if (in_array('senate', $userRoles)) {
    			
    		}

    	}else{

    		//send allow to academic section for users like lecturere, examiner fields
    		session('AccessPermission',1);
    		if(in_array('admin', $userRoles)){
    			//redirect to admin dashboard. does not matter if has other below rows
                return redirect('/admin')->with('status','You have successfully loged in');
    		}else if(in_array('examiner', $userRoles)){
    			//redirect to examiner dashboard. does not matter if has other below rows
                return redirect('/examiner/dashboard')->with('status','You have successfully loged in');
    		}else if (in_array('lecturer', $userRoles)) {
    			//redirect to lecturer dashboard
                return redirect('/lecturer')->with('status','You have successfully loged in');
    		}
    	}
    }


}
