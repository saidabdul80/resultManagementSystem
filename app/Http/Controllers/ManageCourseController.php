<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Maatwebsite\Excel\Facades\Excel;
use \PhpOffice\PhpSpreadsheet\Reader\Xls;
use \App\imports\ImportCourses;
use DB;
use App\Course;
/*require_once(public_path().'/assets/spreadsheet-reader/php-excel-reader/excel_reader2.php');
include ('App/spreadsheetreader/SpreadsheetReader.php');*/
class ManageCourseController extends Controller
{
    //
    public function __construct(){
    	$this->middleware('auth');
    }


    public function show()
    {
    	return view('admin/manage_course');
    }

	 public function Editcourse(Request $request)
    {
    	$type = $request->input('type');
		
		//create course
		if ($type == 1) {
			$chk = Course::where(['course_code'=>$request->input('ccode'), 'department_id'=>$request->input('dept')])->get();
			if ($chk->count()>0) {
				return response()->json(['success'=>201]);
			}else{
				$courseU = new Course;
				$courseU->course_code        = $request->input('ccode');
				$courseU->course_description = $request->input('cdesc');
				$courseU->department_id 	 =  $request->input('dept');
				$courseU->level_id      	 = $request->input('level');
				$courseU->credit_unit   	 = $request->input('cunit');
				$courseU->status   	 = 0;
				$courseU->save();
				return response()->json(['success'=>200]);
			}
			
		}

		//update course
		if($type== -1){
			$id = $request->input('id');
			$chk = Course::whereNotIn('id',[$id])->where(['course_code'=>$request->input('ccode'), 'department_id'=>$request->input('dept')])->get();
			if ($chk->count()>0) {
				return response()->json(['success'=>201]);
			}else{
				$courseU = \App\Course::find($id);
				$courseU->course_code        = $request->input('ccode');
				$courseU->course_description = $request->input('cdesc')??'';
				$courseU->department_id 	 =  $request->input('dept');
				$courseU->level_id      	 = $request->input('level');
				$courseU->credit_unit   	 = $request->input('cunit');
				$courseU->save();
				return response()->json(['success'=>200]);
			}
			

		}
		
		//upload course
		if ($type =2) {
			

			
//			return response()->json(['success'=>200]);
			$request->validate([
				'file'=>'required'
			]);
       
		  $allowedFileType = ['xlsx','xls'];
		  $file = $request->file("file");
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

        $targetPath = 'uploads/courses/';
        $path = public_path('/uploads/courses/'.$filename);
        //$filename;
        //$path = $file->storeAs($targetPath,$);
        //move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        $file->move($targetPath,$filename);
        return view('admin.manage_course', ['path'=>$path, 'departmentuid' => $departmentID] );
      
		  }

		}//end upload course

		if ($type == 11) {
            $sendmsg = $request->input('sendmsg');
            //return dd($sendmsg);
            return back()->with('sendmsg',$sendmsg);            
        }
    }
    
}