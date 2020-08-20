<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Result_trend;
class ExaminerViewResultController extends Controller
{
    //
     //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('/examiner/e_view_result');
    }

    public function withdata($p= null,Request $request)
    {
    	return view('/examiner/e_view_result',['p'=>$p]);	
    }

    public function saveTrend(Request $request){
        
        $passFail = $request->input('pf');
        $level = $request->input('level'); 
        $semester = $request->input('semester');
        $session = $request->input('session');
        $department = $request->input('department');
/*"UPDATE `result_trend` SET `passFail`='$passFail' ,`status`=1 WHERE `level`='$level' AND `semester`='$semester' AND`session`='$session' AND `department`= '$department';"*/
        Result_trend::where(['level_id'=> $level, 'semesters'=> $semester, 'session'=>$session, 'department'=>$department])->update(['passFail'=>$passFail, 'status'=>1]);
        return response()->json(['success'=>200]);
    }
}
