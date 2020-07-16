<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ExaminerManagmentController extends Controller
{
    //
    public function ExaminerPage(Request $request)
    {
    	$type = $request->input('type');
    	if ($type == 1) {

	    	$id = $request->input('id');
			$lectID = $request->input('lectID');
			$fname  = $request->input('fname');

			session(['sel_lect_id'=> $id]);
			session(['lectID'=> $lectID]);
			session(['fname'=> $fname]);
			session(['allocourse'=> 1]);
	    	return response()->json(['success'=>200]);	
    	}

    	if ($type == 2) {
    		$courses = $request->input('subArr');
			$lectid  = $request->input('lec_id');
			$dept    = $request->input('dept');
			$date = date('Y-m-d h:ia');
			$session_id = $request->input('session_id');
			$allData = array();
			$exist =0;
			$exists ='';
    		foreach ($courses as $key => $value) {
			$chk = DB::table('lecturer_allocated_courses', 'l')->join('courses', 'courses.id','=','l.course_id')->where(['l.course_id'=>$value, 'l.session_id'=>$session_id, 'l.department_id'=>$dept])->first('courses.course_code as cc');
			
				if (!is_null($chk)){
					$exist = 3;
					$exists .= $chk->cc.', ';
					//$actionID .= $chk'lecture_ID'].', ';
				}else{
					$allData[] = [
    				'lecturer_id'=> $lectid,
    				'course_id'=> $value,
    				'session_id'=> $session_id,
    				'department_id'=> $dept,
    				'created_by_user_id'=> 0,
    				'created_on'=> $date,
    				'status'=> 0

    				];
				}
			}
			
			if (!is_null($allData)){
				if($exist==3) {
					DB::table('lecturer_allocated_courses')->insert($allData);
				     return response()->json(['success'=>202, 'exists'=>$exists]);
				}else{
					DB::table('lecturer_allocated_courses')->insert($allData);
				     return response()->json(['success'=>200]);
				}
			}else{
                return response()->json(['success'=>201]);
				//exist
			}
    	}
    	if ($type == -2) {
    		$courses = $request->input('subArr');
			$lectid  = $request->input('lec_id');
			$dept    = $request->input('dept');
			$date = date('Y-m-d h:ia');
			$session_id = $request->input('session_id');
			$allData = array();
			$exist =0;
    		foreach ($courses as $key => $value) {
				DB::table('lecturer_allocated_courses')->where('id', '=', $value)->delete();
			}
			return response()->json(['success'=>200]);
    	}

		//return view('/examiner/manage-lecturer');
    }
}
