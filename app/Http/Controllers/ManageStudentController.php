<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use \Illuminate\Support\Facades\Route;

class ManageStudentController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('admin/manage_student');
    }

    

    

    public function EditStudent(Request $request)
    {
    	$type = $request->input('type');
        //add student
        if ($type==1) {
            
            $fcreate = new \App\Student;
            $fcreate->first_name = $request->input('first_name');
            $fcreate->surname = $request->input('surname');
            if ($request->input('other_name')!='') {
                $fcreate->other_name = $request->input('other_name');
            }
            if ($request->input('country') !='') {
                $fcreate->country = $request->input('country');
            }
            if ($request->input('state') != '') {
                $fcreate->state_of_origin= $request->input('state');
            }
            $fcreate->department_id = $request->input('deptID');
            if ($request->input('lga') !='') {
                $fcreate->lga = $request->input('lga');
            }
            $fcreate->phone_number = $request->input('phone');
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
            $fcreate->level_id = 1;
            $fcreate->status = 0;
            $fcreate->save();
            return response()->json(['success'=>200]);  
        }

        //update student
        if ($type == -1) {
            $id = $request->input('id');
            $fcreate = \App\Student::find($id);
            $fcreate->first_name = $request->input('first_name');
            $fcreate->surname = $request->input('surname');
            $fcreate->other_name = $request->input('other_name');
            $fcreate->department_id = $request->input('deptID');
            $fcreate->country = $request->input('country');
            $fcreate->state_of_origin = $request->input('state');
            $fcreate->lga = $request->input('lga');
            $fcreate->phone_number = $request->input('phone');
            $fcreate->email = $request->input('email');
            $fcreate->address = $request->input('address');
            $fcreate->nxt_of_kin_name = $request->input('nkn');
            $fcreate->nxt_of_kin_phone = $request->input('nkp');
            $fcreate->status = 0;
            $fcreate->save();
            return response()->json(['success'=>200]); 
        }

        //change matric number
        if ($type == 4) {
            $nmatric  = $request->input('nmatric');
            $id  = $request->input('id');

            $chk = \App\Student::whereNotIn('id', [$id])->where('matric_number',$nmatric)->get();
            if ($chk->count()<=0) {
                $fcreate = \App\Student::find($id);
                $fcreate->matric_number = $nmatric;
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

            $chk = \App\Student::whereNotIn('id', [$id])->where('email',$email)->get();
            if ($chk->count()<=0) {
                $fcreate = \App\Student::find($id);
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
            $date = date('Y-m-d_h:i:sa');
            $filename = $date.'-'.$file->getClientOriginalName();
         // return $filename;

            $targetPath = 'uploads/student/';
            $path = public_path('/uploads/student/'.$filename);
            //$filename;
            //$path = $file->storeAs($targetPath,$);
            //move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
            $file->move($targetPath,$filename);  
                return view('admin.manage_student', ['path'=>$path, 'departmentuid' => $departmentID] );
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
