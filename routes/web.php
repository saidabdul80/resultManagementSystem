<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//actual to use
Route::get('/', function(){ return view('auth.login'); });
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@Registeration')->name('register');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login/{role_ids}/{user_id}', 'UserController@RoleCheck');
Route::get('/admin/assign-role', 'AssignroleController@show')->name('assign-role');
Route::post('/admin/assign-role', 'AssignroleController@Edit')->name('editUserRole');

//from search class
Route::patch('/admin/assign-role', 'SetSomeSession@assignR');
Route::get('admin/configuration', 'ConfigurationController@show')->name('configuration');


Route::get('admin/manage-course', 'ManageCourseController@show')->name('manage-course');
Route::post('/admin/manage-course', 'ManageCourseController@Editcourse')->name('cuourseCRUD');
//Route::patch('/admin/manage-course', 'SetSomeSession@assignR');
Route::patch('/admin/manage-course', 'SetSomeSession@selectCoures');


//template downloading--------------------------------
Route::get('course_temp', function(){				#
	return Storage::download('courses.xlsx');		#
});													#
Route::get('students_temp', function(){				#
	return Storage::download('students.xlsx');		#	
});													#
//---------------------------------------------------
//-----------------------------------Admin----------------------------------------------------------/////
Route::get('/admin/manage-fadep', 'ManageFadepController@show')->name('manage-fadep');
Route::post('/admin/manage-fadep', 'ManageFadepController@Editfadep')->name('fadepCRUD');
Route::get('/admin/grade', 'GradeController@show')->name('grade');
Route::post('/admin/grade', 'GradeController@sessionsForGrade')->name('selectGradeScale');

Route::get('/admin/manage-session', 'ManageSessionController@show')->name('manage-session');
Route::post('/admin/manage-session', 'ManageSessionController@EditSession')->name('sessionCRUD');
Route::post('/admin/setSemester', 'ManageSessionController@setSemester')->name('setSemester');

Route::get('/admin/manage-student', 'ManageStudentController@show')->name('manage-student');
Route::post('/admin/manage-student', 'ManageStudentController@EditStudent')->name('studentCRUD');
Route::patch('/admin/manage-student', 'SetSomeSession@searchA');

Route::get('admin/manage-user', 'ManageUserController@show')->name('manage-user');
Route::post('/admin/manage-user', 'ManageUserController@EditLecturer')->name('lecturerCRUD');
Route::patch('/admin/manage-user', 'SetSomeSession@searchA');
Route::get('admin/a-logs', 'ALogsController@show')->name('a-logs');


Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('/admin', 'AdminController@Index');
//-----------------------------------End Admin-----------------------------------////////////////////////////


//-------------------Lecturer-----------------------------------------------//////////////////////////////
Route::get('/lecturer/dashboard', 'ManageLecturerDashboardController@show')->name('ldashboard');
Route::get('/lecturer/manage-result', 'ManageLecturerResultController@show')->name('l_manage_result');
Route::get('/lecturer/view-student', 'LecturerViewStudentsController@show')->name('l_view_student');
Route::post('/lecturer/view-student', 'LecturerViewStudentsController@show')->name('l_view_student');
Route::get('/lecturer/logs', 'ManageLecturerLogsController@show')->name('l_logs');
Route::post('/lecturer/download', 'DownloadController@download')->name('result_upload_template');


/*
*/

Route::get('/lecturer/manage-result/{p?}', function($p= null){	
	//session();
	//??session('p')
	return view('lecturer/l_manage_result',['p'=>$p]);
})->name('lchanges')->middleware('auth');

Route::patch('/lecturer/manage-result/{p?}', function($p= null){	
	//session();
	//??session('p')
	return view('lecturer/l_manage_result',['p'=>$p]);
})->name('lchanges')->middleware('auth');


Route::post('/lecturer/manage-result/{p?}', function($p= null,Request $request){
	if (!is_null($request->input('type'))) {
		$type =$request->input('type');
		if ($type==1) {
			$file = $request->file('fileUpload');
            $id = $request->input('id');

            $date = date('Y-m-d_h');
            $token_raw = $request->input('token_raw');
			$filename = $token_raw.'-'.$file->getClientOriginalName();
            $targetPath = 'uploads/results/'.$id.'/';

	            if (is_dir($targetPath)) {
	            	//path to upload
	            	$path = public_path('/uploads/results/'.$id.'/'.$filename);
	            }else{
	            	mkdir(public_path('/uploads/results/'.$id),777);
	            	$path = public_path('/uploads/results/'.$id.'/'.$filename);
	            }

			if($file->move($targetPath, $filename)){
    			return view('lecturer/l_manage_result',['p'=>$p,'path'=>$path,'course_idUpload'=>$request->input('course_idUpload')]);
			}
    		//->with()->with();			
		}else if($type == 2){
			//action('ResultConfirmationController@confirm');
		}else{
			$sendmsg = $request->input('sendmsg');
	        //return dd($sendmsg);
	        return back()->with('sendmsg',$sendmsg); 
		}
	}else{
		session(['selected_course_id'=> $request->input('id')]);
		session(['selected_course_code'=> $request->input('ccode')]);
		return response()->json(['success'=>200]);	
	}
})->name('lchanges')->middleware('auth');

Route::put('/lecturer/manage-result/{p?}','ResultConfirmationController@confirm')->name('confirmResult')->middleware('auth');
Route::DELETE('/lecturer/manage-result/{p?}','ResultConfirmationController@delete')->name('deleteResult')->middleware('auth');


Route::post('/lecturer/manage-result', function(Request $request){	
	session(['selected_course_id'=> $request->input('id')]);
	session(['selected_course_code'=> $request->input('ccode')]);
	return response()->json(['success'=>200]);	
})->name('lchange')->middleware('auth');
//-------------------End Lecturer-------------------------------------------////////////////////////////////


//-------------------Examiner-----------------------------------------------//////////////////////////////
Route::get('/examiner/dashboard', 'ManageExaminerDashboardController@show')->name('edashboard');
Route::get('/examiner/manage-result', 'ExaminerManageResultController@show')->name('e_manage_result');
//Route::get('/examiner/manage-result', 'ExaminerManageResultController@process1')->name('processing_r');
Route::POST('/examiner/manage-result', 'ExaminerManageResultController@process1')->name('process_result');
Route::get('/examiner/manage-lecturer', 'ExaminerManageLecturerController@show')->name('e_manage_lecturer');
Route::get('/examiner/view-result/', 'ExaminerViewResultController@show')->name('e_view_result');
Route::get('/examiner/view-result/{p}', 'ExaminerViewResultController@withdata')->name('echanges');
Route::POST('/examiner/view-result/{p}', 'ExaminerViewResultController@withdata')->name('echanges1');
Route::PUT('/examiner/view-result', 'ExaminerViewResultController@saveTrend')->name('saveTrends');
Route::get('/examiner/e-logs', 'elogsController@show')->name('e_logs');
Route::post('/examiner/manage-lecturer', 'ExaminerManagmentController@ExaminerPage')->name('e-managesession');
//-------------------End Examiner-------------------------------------------////////////////////////////////


//-------------------Examiner-----------------------------------------------//////////////////////////////
Route::get('/faculty/dashboard', 'ManageFacultyDashboardController@show')->name('fdashboard');
Route::get('/faculty/timing', 'FtimingController@show')->name('f_timing');
Route::post('/faculty/timing', 'FtimingController@ManageTime')->name('manage_timing');
Route::get('/faculty/view-result', 'FacultyViewResultController@viewResult')->name('f_view_result');
Route::get('/faculty/view-result/{p}', 'FacultyViewResultController@withdata')->name('fchanges');
Route::POST('/faculty/view-result/{p}', 'FacultyViewResultController@withdata')->name('fchanges1');
Route::get('/faculty/flogs', 'flogsController@show')->name('f_logs');/*
Route::get('/faculty/e-logs', 'elogsController@show')->name('e_logs');
Route::post('/faculty/manage-lecturer', 'ExaminerManagmentController@ExaminerPage')->name('e-managesession');*/
//-------------------End Examiner-------------------------------------------////////////////////////////////



/*Route::get('/login', 'AutoController@login');
Route::post('/login','UserController@Validation');
*/



Route::get('/lecturer', 'LecturerController@index');
Route::get('/home', function (){
/*$as = array('a'=>1212,'b'=>'1we12' );

//return $as;
if (!array_key_exists($a, $as)) {
	abort(404);
}*/
$asd= 'my name';
 return view('welcome', compact('asd'));
}); 

Route::get('/roles','PermissionController@Permission');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
