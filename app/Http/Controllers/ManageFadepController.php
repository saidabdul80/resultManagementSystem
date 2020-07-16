<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Faculty;
use \App\Department;

class ManageFadepController extends Controller
{
    //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('admin/manage_fadep');
    }

    
     public function Editfadep(Request $request)
    {
    	$type = $request->input('type');

    	//create faculty
    	if ($type == 1) {
    		$nfaculty = explode(',',$request->input('nfaculty'));
			$faculty = $nfaculty[0];
			$abbr    = $nfaculty[1];
			if(preg_match('/[¬`\|<>£\$%\^\+=]/', $faculty) || preg_match('/[¬`\|<>£\$%\^\+=]/', $abbr)){
				return response()->json(['success'=>207]);
			}else{
				$chk = Faculty::where('faculty',$faculty)->get();
				if ($chk->count()>0) {
					return response()->json(['success'=>201]);	
				}else{
					$fcreate = new Faculty;
					$fcreate->faculty = $faculty;
					$fcreate->faculty_abbr = $abbr;
					$fcreate->status = 0;
					$fcreate->save();
					return response()->json(['success'=>200]);	

				}
			}

    	}
    	//update faculty
    	if ($type ==-1) {
			$faculty =  $request->input('faculty');
			$id = $request->input('id');
			$abbr    =  $request->input('abbr');
			if(preg_match('/[¬`\|<>£\$%\^\+=]/', $faculty) || preg_match('/[¬`\|<>£\$%\^\+=]/', $abbr)){
				return response()->json(['success'=>207]);
			}else{
				$chk = Faculty::whereNotIn('id',[$id])->where('faculty',$faculty)->get();
				if ($chk->count()>0) {
					return response()->json(['success'=>201]);	
				}else{
					$fupdate = Faculty::find($id);
					$fupdate->faculty = $faculty;
					$fupdate->faculty_abbr = $abbr;
					$fupdate->status = 0;
					$fupdate->save();
					return response()->json(['success'=>200]);	

				}
			}
    	}
    	//create Department
    	if ($type == 2) {
    		$ndepartment = explode(',',$request->input('ndepartment'));
    		$fid = $request->input('fid');
			$department = $ndepartment[0];
			$abbr    = $ndepartment[1];
			if(preg_match('/[¬`\|<>£\$%\^\+=]/', $department) || preg_match('/[¬`\|<>£\$%\^\+=]/', $abbr)){
				return response()->json(['success'=>207]);
			}else{
				$chk = Department::where('department',$department)->get();
				if ($chk->count()>0) {
					return response()->json(['success'=>201]);	
				}else{
					$dcreate = new Department;
					$dcreate->department = $department;
					$dcreate->department_abbr = $abbr;
					$dcreate->faculty_id = $fid;
					$dcreate->status = 0;
					$dcreate->save();
					return response()->json(['success'=>200]);	

				}
			}
    	}
    	//update Department
    	if ($type ==-2) {
    		$department =  $request->input('department');
			$id = $request->input('id');
			$fid = $request->input('fid');
			$abbr    =  $request->input('dabbr');
			if(preg_match('/[¬`\|<>£\$%\^\+=]/', $department) || preg_match('/[¬`\|<>£\$%\^\+=]/', $abbr)){
				return response()->json(['success'=>207]);
			}else{
				$chk = Department::whereNotIn('id',[$id])->where(['department'=>$department, 'faculty_id'=>$fid])->get();
				if ($chk->count()>0) {
					return response()->json(['success'=>201]);	
				}else{
					$dupdate = \App\Department::find($id);
					$dupdate->department = $department;
					$dupdate->department_abbr = $abbr;
					$dupdate->faculty_id = $fid;
					$dupdate->status = 0;
					$dupdate->save();
					return response()->json(['success'=>200]);	

				}
			}
    		
    	}

    }
}
