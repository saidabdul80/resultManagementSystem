<?php

namespace App\Http\Controllers;

//use Illuminate\Database\Eloquent\Model;
//use Spatie\Activitylog\Traits\LogsActivity;
use App\Grade;
use Auth;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    
    
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	$current_grade_scale = Grade::where('c_set',1)->first();
    	//return dd($current_grade_scale->id);
    	session(['current_set_grade_id'=> $current_grade_scale->id]);
    	return view('admin/grade');
    }

    public function sessionsForGrade(Request $request)
    {
    	
		
		$type = $request->input('type');

		//set current grading scale
		if ($type==3) {

			//current set grade id
			$cid = $request->input('csgid');
			//grade name
			$gname = $request->input('gnm');
			//old set grade id
			$oid = $request->input('ogid');
			
			$Grade0 = \App\Grade::find($oid);
			$Grade0->c_set = 0;
			
			$Grade = \App\Grade::find($cid);
			$Grade->c_set = 1;

			$Grade0->save();
			$Grade->save();
			return response()->json(['success'=>200]);
		}

		//set session and unset session variables
    	if($type==2 || $type ==-2){
	    	$updatess = $request->input('update');
			$idk = $request->input('id');

			if ($type==-2) {
				session(['grade_selected_id'=> null]);
				session(['grade_update'=> null]);
			}
			if ($type==2) {
				session(['grade_selected_id'=> $idk]);
				session(['grade_update'=>$updatess]);
			}
    	}


		if($type==1 || $type== -1){
			$gname = $request->input('gname');
				$aa = $request->input('aa');
				$bb = $request->input('bb');
				$cc = $request->input('cc');
				$dd = $request->input('dd');
				$ee = $request->input('ee');
				$ff = $request->input('ff');
				$co = $request->input('co');
			if ($type==1) {

				$allgrades = Grade::get();
				
				$allgradesA = array();
				foreach ($allgrades as $gname1) {
					$allgradesA[] = $gname1->name;
				}
				
				//validate grading if exist throw 201
				if (in_array($gname, $allgradesA)) {
					return 201;
				}else{					
				
				//create new grade
					$Grade = new Grade;
					$Grade->name = $gname;
					$Grade->A = $aa;
					$Grade->B = $bb;
					$Grade->C = $cc;
					$Grade->D = $dd;
					$Grade->E = $ee;
					$Grade->F = $ff;
					$Grade->CO = $gg;
					$Grade->created_by = Auth::id();
					$Grade->c_set = 0;
					$Grade->status = 0;
					$Grade->save();
					return response()->json(['success'=>200]);
				}


			}
			if ($type==-1) {
			
				$idk = $request->input('id');

				//fetch all grade scale
				$allgrades = Grade::whereNotIn('id',[$idk])->get();
				$allgradesA = array();
				foreach ($allgrades as $gname1) {
					$allgradesA[] = $gname1->name;
				}

				//validate grading if exist throw 201				
				if (in_array($gname, $allgradesA)) {
					return 201;
				}else{					
					//update grade scale
					$Grade = \App\Grade::find($idk);
					$Grade->name = $gname;
					$Grade->A = $aa;
					$Grade->B = $bb;
					$Grade->C = $cc;
					$Grade->D = $dd;
					$Grade->E = $ee;
					$Grade->F = $ff;
					$Grade->save();
					return response()->json(['success'=>200]);
				}
			}
		}

		
    	//return dd($id);
    	
		/*
		return view('admin/grade',[
			'grade_selected_id' => $idk,
			'grade_update'      => $updatess,
		]);*/
    }

    
}

