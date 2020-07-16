<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetSomeSession extends Controller
{
    public function assignR(Request $request)
    {
    	$departmentid = $request->input('seldepartment');
		$facultyid    = $request->input('selFaculty');
		$facultyname  = $request->input('type');

		session(['departmentid'=> $departmentid]);
		session(['facultyid'=> $facultyid]);
		session(['facultyname'=> 1]);
			//update user role
		  
		$uri = explode('/',url()->current());
 		$uri =  end($uri);
		$url =  str_replace('-', '_', $uri); 

		$urii = explode('/',url()->current());
		$folder = $urii[sizeof($urii)-2];
    	return view($folder.'/'.$url);
    }

    

    public function selectCoures(Request $request)
    {
    	$selsemester = $request->input('id');

		session(['course_selected_id'=> $selsemester]);
		session(['course_update'=> 'update']);
 
		$uri = explode('/',url()->current());
 		$uri =  end($uri);
		$url =  str_replace('-', '_', $uri); 

		$urii = explode('/',url()->current());
		$folder = $urii[sizeof($urii)-2];
    }

    public function searchA(Request $request)
    {
    	$departmentid = $request->input('seldepartment');
    	$facultyid = $request->input('selFaculty');
    	
    	
		session(['departmentid'=> $departmentid]);
		session(['facultyid'=> $facultyid]);
		
		session(['update'=> 'update']);

		$uri = explode('/',url()->current());
 		$uri =  end($uri);
		$url =  str_replace('-', '_', $uri); 

		$urii = explode('/',url()->current());
		$folder = $urii[sizeof($urii)-2];
    	return view($folder.'/'.$url);
    }
}
