<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Session;
class ManageSessionController extends Controller
{
    //

    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('/admin/manage_session');
    }

    public function EditSession(Request $request)
    {
    	$type = $request->input('type');

    	//create session
    	if($type ==1 || $type == -1){
    		$session = $request->input('session');
	    	if(preg_match('/["\'¬`\|<>£\$%\^\[\]&\(\)-\+=\{\}\?]/', $session)){
				//if(preg_match('/["\'¬`\|<>£\$%\^\[\]&\(\)-_\+=\{\}\?]/', $session)){
					return response()->json(['success'=>207]);
			}else{
				if (preg_match('/[a-z]/', $session)) {
					return response()->json(['success'=>207]);
				}else{
					if (preg_match('/[A-Z]/', $session)) {
						return response()->json(['success'=>207]);
					}else{

				    	if ($type == 1) {
				    		$chk = Session::where('session',$session)->get();
							if ($chk->count()>0) {
								return response()->json(['success'=>201]);	
							}else{
								$fcreate = new Session;
								$fcreate->session = $session;
								$fcreate->status = 0;
								$fcreate->save();
								return response()->json(['success'=>200]);	

							}
				    	}
				    	//edit session
				    	if ($type == -1) {
				    		$id = $request->input('id');
				    		$chk = \App\Session::whereNotIn('id',[$id])->where('Session',$session)->get();
							if ($chk->count()>0) {
								return response()->json(['success'=>201]);	
							}else{
								$fupdate = Session::find($id);
								$fupdate->session= $session;
								$fupdate->save();
								return response()->json(['success'=>200]);	
							}
				    	}
					}
		    	}
		    	//set semester
			}
		}
	   	if($type == 3){

				$new_session_id = $request->input('new_session_id');
				$old_session_id = $request->input('old_session_id');

				$Grade0 = Session::find($old_session_id);
				$Grade0->c_set = 0;
				
				$Grade = Session::find($new_session_id);
				$Grade->c_set = 1;

				$Grade0->save();
				$Grade->save();
				return response()->json(['success'=>200]);

	    	}
	}

}
