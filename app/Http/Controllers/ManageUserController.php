<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('admin/manage_user');
    }

       public function EditLecturer(Request $request)
    {
    	$type = $request->input('type');
        //add Lecturer
        if ($type==1) {
            
            $ucreate = new \App\User;
            $ucreate->name =  $request->input('first_name');
            $ucreate->email =  $request->input('email');
            $ucreate->password =  bcrypt($request->input('phone'));
            $ucreate->role_id =  5;//user role
            $ucreate->save();
            $last_id = $ucreate->id;

            $fcreate = new \App\Lecturer;
            $fcreate->lecture_ID = $request->input('lecturerID');
            $fcreate->first_name = $request->input('first_name');
            $fcreate->surname = $request->input('surname');
            $fcreate->department_id = $request->input('departmentID');
          
            if ($request->input('country') !='') {
                $fcreate->country = $request->input('country');
            }
            if ($request->input('state') != '') {
                $fcreate->state= $request->input('state');
            }
            $fcreate->department_id = $request->input('deptID');
            if ($request->input('lga') !='') {
                $fcreate->lga = $request->input('lga');
            }
            $fcreate->phone = $request->input('phone');
            $fcreate->email = $request->input('email');
            if ($request->input('address') !='') {
                $fcreate->address = $request->input('address');
            }
            if ($request->input('nkn') !='') {
                $fcreate->nxt_of_kin_name = $request->input('nkn');
            }
            if ($request->input('nkp') !='') {
                $fcreate->nxt_of_kin_phone = $request->input('nkp');
            }
            if ($request->input('salute') !='') {
                $fcreate->salute = $request->input('salute');
            }
            $fcreate->user_id = $last_id;
            $fcreate->status = 0;
            $fcreate->save();
            return response()->json(['success'=>200]);  
        }

        //update Lecturer
        if ($type == -1) {
        	$phone = $request->input('phone');
        	$email = $request->input('email');
        	
        	if ( $phone!='' || $email !='' ) {
        		
        		$uid = \App\User::where('email',$email)->first()->id;
        		$ucreate = \App\User::find($uid);
        		
        		if($phone !='') {
	            	$ucreate->password = bcrypt($phone);
        		}

        		if ($email != '') {
	            	$ucreate->email =  $email;
        		}
	            $ucreate->save();

        	}

            $id = $request->input('id');
            $fcreate = \App\Lecturer::find($id);
            //return  $id;
             $fcreate->first_name = $request->input('first_name');
            $fcreate->surname = $request->input('surname');
            $fcreate->department_id = $request->input('departmentID');
          
            if ($request->input('country') !='') {
                $fcreate->country = $request->input('country');
            }
            if ($request->input('state') != '') {
                $fcreate->state= $request->input('state');
            }
            $fcreate->department_id = $request->input('deptID');
            if ($request->input('lga') !='') {
                $fcreate->lga = $request->input('lga');
            }
            $fcreate->phone = $request->input('phone');
            $fcreate->email = $request->input('email');
            if ($request->input('address') !='') {
                $fcreate->address = $request->input('address');
            }
            if ($request->input('nkn') !='') {
                $fcreate->nxt_of_kin_name = $request->input('nkn');
            }
            if ($request->input('nkp') !='') {
                $fcreate->nxt_of_kin_phone = $request->input('nkp');
            }
            if ($request->input('salute') !='') {
                $fcreate->salute = $request->input('salute');
            }
            $fcreate->status = 0;
            $fcreate->save();
            return response()->json(['success'=>200]); 
        }

        //change matric number
        if ($type == 4) {
            $lecturerID  = $request->input('nlecturerID');
            $id  = $request->input('id');
           //return $lecturerID;
            $chk = \App\Lecturer::whereNotIn('id', [$id])->where('lecture_ID',$lecturerID)->get();
            if ($chk->count()<1) {
                $fcreate = \App\Lecturer::find($id);
                $fcreate->lecture_ID = $lecturerID;
                $fcreate->save();
                return response()->json(['success'=>200]);
            }else{
                return response()->json(['success'=>201]);
            }
        }

        //change matric number
        if ($type == -4) {
            $email  = $request->input('email');
            $id  = $request->input('id');

            $chk = \App\Lecturer::whereNotIn('id', [$id])->where('email',$email)->get();
            if ($chk->count()<=0) {
                $fcreate = \App\Lecturer::find($id);
                $fcreate->email = $email;
                $fcreate->save();
                return response()->json(['success'=>200]);
            }else{
                return response()->json(['success'=>201]);
            }
        }

        //
        if($type ==10) {
          
       
          $allowedFileType = ['xlsx','xls'];
          $file = $request->file("file");
          $departmentu = $request->file("departmentu");
          if(in_array($file->extension(),$allowedFileType)){
          //return 2;
            $success =0;
            $error =0;
            $FailedLines ='';
            $existCourse ='';
            $departmentID =$request->input('departmentu');
            $date = date('Y-m-d_h');
            $filename = $date.'-'.$file->getClientOriginalName();
         // return $filename;

            $targetPath = 'uploads/Lecturer/';
            $path = public_path('/uploads/Lecturer/'.$filename);
            //$filename;
            //$path = $file->storeAs($targetPath,$);
            //move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
            $file->move($targetPath,$filename);  
                return view('admin.manage_user', ['path'=>$path, 'departmentuid' => $departmentID] );
            }
        }

        //return msg
        if ($type == 11) {
            $sendmsg = $request->input('sendmsg');
            //return dd($sendmsg);
            return back()->with('sendmsg',$sendmsg);            
        }
    }
}
