<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\f_timing;
class FtimingController extends Controller
{
    //
      //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('/faculty/f_timing');
    }

    public function ManageTime(Request $request){

    	$type = $request->input('type');
    	$session = $request->input('session');
    	$semester = $request->input('semester');
    	$faculty = $request->input('faculty');

    	if ($type == 1) {
    		$tm = f_timing::where(['faculty'=>$session,'session'=>$session, 'semester' =>$semester])->first();

    		if (is_null($tm)) {    		
    			$create = new f_timing;
    			$create->faculty = $faculty;
    			$create->session = $session;
    			$create->semester = $semester;
    			$create->startsT = '01-01-2000';
    			$create->endT = '01-01-2000';
    			$create->status = '1';
    			$create->save();
    		}else{
    			$update = f_timing::find($tm->id);
    			if ($tm->status == 1) {
    				$update->status = 1;
    			}else{
    				$update->status = 0;
    			}
    			$update->save();
    		}
			return response()->json(['success'=>200]);	
    	}

    	if ($type ==2) {
    		$time1 = $request->input('tim1');
    		$time2 = $request->input('tim2');
    		$tm = f_timing::where(['faculty'=>$faculty,'session'=>$session, 'semester' =>$semester])->first();
    		$update = f_timing::find($tm->id);

    		$update->startsT = $time1;
    		$update->endT = $time2;
    		$update->save();
			return response()->json(['success'=>200]);	
    	}

    }
}
