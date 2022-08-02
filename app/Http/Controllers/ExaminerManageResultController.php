<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class ExaminerManageResultController extends Controller
{
    //
     //
    public function __construct(){
    	$this->middleware('auth');
    }

    public function show()
    {
    	return view('/examiner/e_manage_result');
    }
    public function process1(Request $request)
    {

        function levl($v){
            if ($v==100) {
                return 1;
            }elseif($v==200){
                return 2;
            }elseif($v==300){
                return 3;
            }elseif($v==400){
                return 4;
            }elseif($v==500){
                return 5;
            }
        }
        $allCourse = $request->input('allCourse');
        $level = $request->input('level')[0];
        $semester = $request->input('semester');
        $session = $request->input('session');
        $department = $request->input('departmentID');
        //$token = $request->input('rtoken');
        $date = date('Y-m-d h:ia');
        $type = $request->input('type');
        $st = \DB::table('semesters')->where('c_set',1)->first();
        $currentsemester = $st->id;
        $currentsession = str_replace('/', '-', \DB::table('sessions')->where('c_set',1)->first()->session );
        $token = $currentsession.'-'.$currentsemester.'-';

        
        $injson = [

            "attributes"=>[
                $currentsession.'-'.$currentsemester.'-'.$request->input('level')."level Resulty compiled",
                "created_by" => Auth::user()->id,
                "created_on"=> $date
            ]
        ];
        
//*******************************LOG ACTIVITY*****************************************************************

    \DB::table('activity_log')->insert(['id'=>null,'log_name'=>'default','description'=>'touched','subject_id'=>Auth::user()->id, 'subject_type' => 'App\compiled_r', 'causer_id' => Auth::user()->id, 'causer_type' => 'App\compiled_r','properties' => json_encode($injson), 'created_at' => $date, 'updated_at' => $date ]);
        /*$logs =  new \App\activity_log;
        $logs->log_name = 'default';
        $logs->description = 'touched';
        $logs->subject_id = Auth::user()->id;
        $logs->subject_type = 'App\compiled_r';
        $logs->causer_id = Auth::user()->id;
        $logs->causer_type = 'App\compiled_r';        
        $logs->properties = '{"attributes":{"'.$currentsession.'-'.$currentsemester.'-'.$request->input('level').'level Resulty compiled","created_by":"'.Auth::user()->id.'","created_on":"'.$date.'"}}';
        $logs->created_at = $date;
        $logs->updated_at = $date;
        $logs->save();*/
//******************************* END LOG ACTIVITY*****************************************************************
                /*
        $t_raw = explode('-', $_POST['token']);
        $token = $t_raw[0].'-'.$t_raw[1].'-'.$t_raw[2].'-';
        $year = $t_raw[0].'-'.$t_raw[1];*/
        $msg = array();
        //session(['ARR'=> $ARR]);


    
        //$fetch_all_students = $conn->query("SELECT * FROM students_registered_courses as sr INNER JOIN students as s ON sr.student_id=s.id WHERE s.department_id='$department' AND sr.session_id='$session' AND sr.semester='$semester' AND sr.level_id='$level' ORDER BY s.matric_number ASC");
        $fetch_all_students = \DB::table('students_registered_courses', 'sr')
                                ->join('students', 'students.id', '=', 'sr.student_id')
                                ->where(['students.department_id'=> $department, 'sr.session_id'=>$session, 'sr.semester'=> $semester, 'sr.level_id'=>$level])
                                ->orderBy('students.matric_number', 'ASC')
                                ->get();

        $c =0;
        $students = array();
        $lastid = 0;
        //echo mysqli_error($conn);
        if($fetch_all_students->count()>0) {
            foreach($fetch_all_students as $fs) {
                $sid = $fs->student_id;
                if($c == 0) {
                    $students[$sid] = array();
                    $students[$sid][] = $fs->course_id;
                    $lastid = $sid;
                    $c++;
                }else{
                    if($lastid==$sid) {
                        $students[$sid][] = $fs->course_id;
                        $lastid = $sid;
                    }else{
                        $students[$sid] = array();
                        $students[$sid][] = $fs->course_id;
                        $lastid = $sid;
                    }
                }
            }   
        }

        $failG_array1 = array('D','E','F');
        $failG_array2 = array('E','F');
        $failG_array3 = array('F');

        //$cu = $conn->query("SELECT * FROM department_cu WHERE session_id='$session' AND semester='$semester'");
        $cuu = \DB::table('department_cu')
                ->where(['session_id'=>$session, 'semester'=>$semester])
                ->first();
        if ( !is_null($cuu)) {
          //  $cuu = $cu->fetch_assoc();

            //department cp
            $ll = $request->input('level').'L';
            $CPP =explode('-', $cuu->$ll);
            $dcup = $CPP[0];
            $dcp = $CPP[1];

            //get where CO starts from grades
            //$getco = $conn->query("SELECT CO FROM grades WHERE c_set=1");
            $sco = \DB::table('grades')->where('c_set', 1)->first();
            if(!is_null($sco)) {
                
                $co_start = $sco->CO;
                    
                if (in_array($co_start, $failG_array3)) {
                    $failG_array = $failG_array3;
                }elseif(in_array($co_start, $failG_array2)) {
                    $failG_array = $failG_array2;
                }elseif(in_array($co_start, $failG_array1)) {
                    $failG_array = $failG_array1;
                }


        #`````````````START CHECK compiled result for either session of semester ``````````

                //$chk_compile = $conn->query("SELECT * FROM compiled_r WHERE session='$session' AND semester='$semester' AND level='$level' AND department='$department'");
                $chk_compile = \DB::table('compiled_r')->where(['session'=>$session, 'semester'=>$semester, 'level'=>$level, 'department'=>$department])->get();
                //echo mysqli_error($conn);
                if($chk_compile->count()>0){
                    if ($type==0) {
                       // echo 10001;
                        return response()->json(['success'=>10001]);                        
                    }else{
                        
        #---------------UPDATE COMPILED RESULT IF RECOMPILING--/--/--/--/--/--/--/--/--/--/--/--/--/--/--/--/--/
        ##################################compiled processes starts###############################################
                        $INNSER_QUERY_ALL = [];
                        foreach ($students as $student => $course_id)
                        {
                           // $chk = $conn->query("SELECT * FROM spread_gp WHERE student_id='$student' AND year!='$session' AND semester !='$semester' AND level_id != '$level' ORDER BY id DESC LIMIT 1");
                            $sgp = \DB::table('spread_gp')->where('students_id','=',$student)->where('year', '<>', $session)->where('semester', '<>', $semester)->where('level_id','<>',$level)->orderBy('id', 'DESC')->first();

                            if (!is_null($sgp)) {
                                //not first exam sheet for this student

                                //it checks if students result already compiled for this semester or not
                                //$chk1 = $conn->query("SELECT * FROM spread_gp WHERE student_id='$student' AND year='$session' AND semester ='$semester' AND level_id = '$level'");
                                $chk1 = \DB::table('spread_gp')->where('students_id','=',$student)->where('year', '=', $session)->where('semester', '=', $semester)->where('level_id','=',$level);
                                
                                if ($chk1->count()>0) {
                                    #*******not a new student bt has not been compiled, now compile from recompiling stage***
                                   // $sgp = $chk->fetch_assoc();
                                    $NSS = $sgp->NSS + 1; 

                                    //registered credit unit course {rcu}
                                    $RCU = 0;
                                    $ECU = 0;
                                    $CP = 0;
                                    $failedCourses ='';
                                    foreach ($course_id as $key => $course) {
                                        /*$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
                                        $r = $rrcu->fetch_assoc();*/
                                        $r = \DB::table('courses')->where('id', $course)->first();
                                        $RCU += $r->credit_unit;
                                            
                                        //geting students grades in this course
                                        $token_raw = $token.$r->course_code;
                                        //$getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
                                            
                                        //$stR = $getG->fetch_assoc();
                                        $stR = \DB::table('results')->where(['result_token'=> $token_raw, 'students_id'=>$student, 'course_id'=>$course])->first();
                                        $stGrade = $stR->grades;
                                            
                                        if(in_array($stGrade, $failG_array)) {
                                        
                                            $failedCourses .= $r->course_code.', ';
                                            if($stGrade=='D') {
                                                $CP +=  $r->credit_unit *2;
                                            }elseif($stGrade=='E') {
                                                $CP +=  $r->credit_unit *1;
                                            }
                                        
                                        }else{
                                        
                                            $ECU += $r->credit_unit;
                                        
                                            if($stGrade=='A'){
                                                $CP +=  $r->credit_unit * 5;
                                            }elseif($stGrade=='B') {
                                                $CP +=  $r->credit_unit * 4;
                                            }elseif($stGrade=='C') {
                                                $CP +=  $r->credit_unit * 3;
                                            }elseif($stGrade=='D') {
                                                $CP +=  $r->credit_unit * 2;
                                            }elseif($stGrade=='E') {
                                                $CP +=  $r->credit_unit * 1;
                                            }
                                        }

                                    }
                                    
                                    $GPA = number_format(($CP/$dcp) * 5, 2);
                                    $TCP = $CP + $sgp['TCP'];
                                    //TDCP total department credit point
                                    $TDCP = $sgp['TDCP'] +$dcp;
                                    $CGPA = number_format(($TCP/$TDCP)*5,2);
                                    $PCGPA = $sgp['CGPA'];
                                    $TRCU = $RCU + $sgp['TRCU'];
                                    $TECU = $ECU + $sgp['TECU'];

                                    //INSERT SPREAD SHEET IN DATABASE
                                   /* $getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
                                    $gt = $getStudent->fetch_assoc();*/
                                    $gt = \DB::table('students')->where('id', $student)->first();
                                    $matric_number = $gt->matric_number;

                                    
                                    //$UPDATE = $conn->query("UPDATE `spread_gp` SET  `RCU`='$RCU', `ECU`='$ECU', `CP`='$CP', `GPA`='$GPA', `TRCU`='$TRCU', `TECU`='$TECU', `TCP`='$TCP', `TDCP`='$TDCP', `PCGPA`='$PCGPA', `CGPA`='$CGPA', `COs`='$failedCourses' WHERE `student_id`='$student' AND `year`='$session' AND `semester`='$semester' AND `department_id`='$department' AND `level_id`='$level'");

                                    $UPDATE = \App\spread_gp::where(['students_id'=>$student, 'year'=>$session, 'semester'=>$semester, 'department_id'=>$department, 'level_id'=>$level])
                                                ->update([
                                                    'RCU'=> $RCU,
                                                    'ECU'=>$ECU,
                                                    'CP'=>$CP,
                                                    'GPA'=>$GPA,
                                                    'TRCU'=>$TRCU,
                                                    'TECU'=>$TECU,
                                                    'TCP'=>$TCP,
                                                    'TDCP'=>$TDCP,
                                                    'PCGPA'=>$PCGPA,
                                                    'CGPA'=>$CGPA,
                                                    'COs'=>$failedCourses
                                                ]);

                                    //echo mysqli_error($conn);

                                    if($UPDATE){
                                        $msg = 'u1';
                                    }else{
                                        if($msg==1){
                                            $msg == 'u01';
                                        }else{
                                         $msg = 'u0';
                                        }
                                    }
                                        
                                    #***********END
                                }else{
                                    #*******not a new student bt has not been compiled, now compile from recompiling stage***
                                   // $sgp = $chk->fetch_assoc();
                                    $NSS = $sgp->NSS + 1; 

                                        //registered credit unit course {rcu}
                                        $RCU = 0;
                                        $ECU = 0;
                                        $CP = 0;
                                        $failedCourses ='';
                                        foreach ($course_id as $key => $course) {

                                            $r = \DB::table('courses')->where('id', $course)->first();
                                            $RCU += $r->credit_unit;
                                            
                                            //geting students grades in this course
                                            $token_raw = $token.$r->course_code;
                                           /* $getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
                                            
                                            $stR = $getG->fetch_assoc();
                                            $stGrade = $stR['grade'];*/

                                             $stR = \DB::table('results')->where(['result_token'=> $token_raw, 'students_id'=>$student, 'course_id'=>$course])->first();
                                            $stGrade = $stR->grades;
                                            
                                            if(in_array($stGrade, $failG_array)) {
                                                $failedCourses .= $r->course_code.', ';
                                                if($stGrade=='D') {
                                                    $CP +=  $r->credit_unit * 2;
                                                }elseif($stGrade=='E') {
                                                    $CP +=  $r->credit_unit * 1;
                                                }
                                            }else{
                                                $ECU += $r->credit_unit;
                                                if($stGrade=='A'){
                                                    $CP +=  $r->credit_unit * 5;
                                                }elseif($stGrade=='B') {
                                                    $CP +=  $r->credit_unit * 4;
                                                }elseif($stGrade=='C') {
                                                    $CP +=  $r->credit_unit * 3;
                                                }elseif($stGrade=='D') {
                                                    $CP +=  $r->credit_unit * 2;
                                                }elseif($stGrade=='E') {
                                                    $CP +=  $r->credit_unit * 1;
                                                }
                                            }


                                        }
                                        $GPA = number_format(($CP/$dcp) * 5, 2);
                                        $TCP = $CP + $sgp['TCP'];
                                        //TDCP total department credit point
                                        $TDCP = $sgp->TDCP +$dcp;
                                        $CGPA = number_format(($TCP/$TDCP)*5,2);
                                        $PCGPA = $sgp->CGPA;
                                        $TRCU = $RCU + $sgp->TRCU;
                                        $TECU = $ECU + $sgp->TECU;

                                        //INSERT SPREAD SHEET IN DATABASE
                                    /*$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
                                    $gt = $getStudent->fetch_assoc();
                                    $matric_number = $gt['matric_number'];*/
                                     $gt = \DB::table('students')->where('id', $student)->first();
                                    $matric_number = $gt->matric_number;
                                    \DB::table('spread_gp')->insert([
                                                    'students_id'=> $student,
                                                    'NSS'=> $NSS,
                                                    'RCU'=> $RCU,
                                                    'ECU'=>$ECU,
                                                    'CP'=>$CP,
                                                    'GPA'=>$GPA,
                                                    'TRCU'=>$TRCU,
                                                    'TECU'=>$TECU,
                                                    'TCP'=>$TCP,
                                                    'TDCP'=>$TDCP,
                                                    'PCGPA'=>$PCGPA,
                                                    'CGPA'=>$CGPA,
                                                    'COs'=>$failedCourses,
                                                    'year'=>$session,
                                                    'semester'=>$semester,
                                                    'department_id'=>$department,
                                                    'level_id'=>$level,
                                                    'ncount' => '0'
                                                ]);
                                    //$insertT = $conn->query("INSERT INTO `spread_gp`(`id`, `student_id`, `NSS`, `RCU`, `ECU`, `CP`, `GPA`, `TRCU`, `TECU`, `TCP`, `TDCP`, `PCGPA`, `CGPA`, `COs`, `year`, `semester`, `department_id`, `level_id`, `ncount`) VALUES(null, '$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')");
                                    //$INNSER_QUERY_ALL[] = "";

                                    #*******End
                                }

                                
                            }else{
                                //$chk1 = $conn->query("SELECT * FROM spread_gp WHERE student_id='$student' AND year='$session' AND semester ='$semester' AND level_id = '$level'");
                                $chk1 = \DB::table('spread_gp')->where('students_id','=',$student)->where('year', '=', $session)->where('semester', '=', $semester)->where('level_id','=',$level);
                                if ($chk1->count()>0) {
                                    //fist time exam student spread sheet BUT RECOMPILING
                                    $NSS = 1;

                                    //registered credit unit course {rcu}
                                    $RCU = 0;
                                    $ECU = 0;
                                    $CP = 0;
                                    $failedCourses ='';
                                    foreach ($course_id as $key => $course) {
                                       /* $rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
                                        $r = $rrcu->fetch_assoc();
                                        $RCU += $r['credit_unit'];*/

                                        $r = \DB::table('courses')->where('id', $course)->first();
                                        $RCU += $r->credit_unit;
                                        //echo $student ;
                                        //geting students grades in this course
                                        $token_raw = $token.$r->course_code;
                                       /* $getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
                                            
                                        $stR = $getG->fetch_assoc();
                                        $stGrade = $stR['grade'];
*/
                                        $stR = \DB::table('results')->where(['result_token'=> $token_raw, 'students_id'=>$student, 'course_id'=>$course])->first();
                                        $stGrade = $stR->grades;
                                            
                                        if(in_array($stGrade, $failG_array)) {
                                            $failedCourses .= $r->course_code.', ';
                                            if($stGrade=='D') {
                                                $CP +=  $r->credit_unit*2;
                                            }elseif($stGrade=='E') {
                                                $CP +=  $r->credit_unit*1;
                                            }
                                        }else{
                                            $ECU += $r->credit_unit;
                                            if($stGrade=='A'){
                                                $CP +=  $r->credit_unit * 5;
                                            }elseif($stGrade=='B') {
                                                $CP +=  $r->credit_unit * 4;
                                            }elseif($stGrade=='C') {
                                                $CP +=  $r->credit_unit * 3;
                                            }elseif($stGrade=='D') {
                                                $CP +=  $r->credit_unit * 2;
                                            }elseif($stGrade=='E') {
                                                $CP +=  $r->credit_unit * 1;
                                            }
                                        }

                                    }
                                    $GPA = number_format(($CP/$dcp) * 5,2) ;
                                    $TCP = $CP;
                                    //TDCP total department credit point
                                    $TDCP = $dcp;
                                    $CGPA = $GPA;
                                    $PCGPA = '-';
                                    $TRCU = $RCU;
                                    $TECU = $ECU;
                                    
                                    //INSERT SPREAD SHEET IN DATABASE
                                    /*$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
                                    $gt = $getStudent->fetch_assoc();
                                    $matric_number = $gt['matric_number'];  */
                                    $gt = \DB::table('students')->where('id', $student)->first();
                                    $matric_number = $gt->matric_number;

                                   // $UPDATE = $conn->query("UPDATE `spread_gp` SET  `NSS`='$NSS', `RCU`='$RCU', `ECU`='$ECU', `CP`='$CP', `GPA`='$GPA', `TRCU`='$TRCU', `TECU`='$TECU', `TCP`='$TCP', `TDCP`='$TDCP', `PCGPA`='$PCGPA', `CGPA`='$CGPA', `COs`='$failedCourses' WHERE `student_id`='$student' AND `year`='$session' AND `semester`='$semester' AND `department_id`='$department' AND `level_id`='$level'");
                                    //echo mysqli_error($conn);

                                     $UPDATE = \DB::table('spread_gp')->where(['students_id'=>$student, 'year'=>$session, 'semester'=>$semester, 'department_id'=>$department, 'level_id'=>$level])
                                                ->update([
                                                    'NSS'=> $NSS,
                                                    'RCU'=> $RCU,
                                                    'ECU'=>$ECU,
                                                    'CP'=>$CP,
                                                    'GPA'=>$GPA,
                                                    'TRCU'=>$TRCU,
                                                    'TECU'=>$TECU,
                                                    'TCP'=>$TCP,
                                                    'TDCP'=>$TDCP,
                                                    'PCGPA'=>$PCGPA,
                                                    'CGPA'=>$CGPA,
                                                    'COs'=>$failedCourses
                                                ]);

                                        $msg = 'u1';/*
                                    if($UPDATE)
                                    {
                                    }
                                    else
                                    {
                                        if($msg==1){
                                            $msg == 'u01';
                                        }else{
                                            $msg = 'u0';
                                        }
                                    }*/
                                }else{
                                    //fist time exam student spread sheet
                                    $NSS = 1;

                                    //registered credit unit course {rcu}
                                    $RCU = 0;
                                    $ECU = 0;
                                    $CP = 0;
                                    $failedCourses ='';
                                    foreach ($course_id as $key => $course) {
                                        /*$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
                                        $r = $rrcu->fetch_assoc();
                                        $RCU += $r['credit_unit'];*/
                                        $r = \DB::table('courses')->where('id', $course)->first();
                                        $RCU += $r->credit_unit;
                                        //echo $student ;
                                        //geting students grades in this course
                                        $token_raw = $token.$r->course_code;
                                        /*$getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
                                            
                                        $stR = $getG->fetch_assoc();
                                        $stGrade = $stR['grade'];*/

                                        $stR = \DB::table('results')->where(['result_token'=> $token_raw, 'students_id'=>$student, 'course_id'=>$course])->first();
                                        $stGrade = $stR->grades;
                                            
                                        if(in_array($stGrade, $failG_array)) {
                                            $failedCourses .= $r->course_code.', ';
                                            if($stGrade=='D') {
                                                $CP +=  $r->credit_unit *2;
                                            }elseif($stGrade=='E') {
                                                $CP +=  $r->credit_unit*1;
                                            }
                                        }else{
                                            $ECU += $r->credit_unit;
                                            if($stGrade=='A'){
                                                $CP +=  $r->credit_unit*5;
                                            }elseif($stGrade=='B') {
                                                $CP +=  $r->credit_unit*4;
                                            }elseif($stGrade=='C') {
                                                $CP +=  $r->credit_unit*3;
                                            }elseif($stGrade=='D') {
                                                $CP +=  $r->credit_unit*2;
                                            }elseif($stGrade=='E') {
                                                $CP +=  $r->credit_unit*1;
                                            }
                                        }

                                    }
                                    $GPA = number_format(($CP/$dcp) * 5,2) ;
                                    $TCP = $CP;
                                    //TDCP total department credit point
                                    $TDCP = $dcp;
                                    $CGPA = $GPA;
                                    $PCGPA = '-';
                                    $TRCU = $RCU;
                                    $TECU = $ECU;
                                    
                                    //INSERT SPREAD SHEET IN DATABASE
                                    /*$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
                                    $gt = $getStudent->fetch_assoc();
                                    $matric_number = $gt['matric_number'];  */

                                    $gt = \DB::table('students')->where('id', $student)->first();
                                    $matric_number = $gt->matric_number;

                                 //   $insertU = $conn->query("INSERT INTO `spread_gp`(`id`, `student_id`, `NSS`, `RCU`, `ECU`, `CP`, `GPA`, `TRCU`, `TECU`, `TCP`, `TDCP`, `PCGPA`, `CGPA`, `COs`, `year`, `semester`, `department_id`, `level_id`, `ncount`) VALUES(null, '$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')");

                                    $insertU = \DB::table('spread_gp')->insert([
                                                    'students_id'=> $student,
                                                    'NSS'=> $NSS,
                                                    'RCU'=> $RCU,
                                                    'ECU'=>$ECU,
                                                    'CP'=>$CP,
                                                    'GPA'=>$GPA,
                                                    'TRCU'=>$TRCU,
                                                    'TECU'=>$TECU,
                                                    'TCP'=>$TCP,
                                                    'TDCP'=>$TDCP,
                                                    'PCGPA'=>$PCGPA,
                                                    'CGPA'=>$CGPA,
                                                    'COs'=>$failedCourses,
                                                    'year'=>$session,
                                                    'semester'=>$semester,
                                                    'department_id'=>$department,
                                                    'level_id'=>$level,
                                                    'ncount' => '0'
                                                ]);

                                        $msg = 'u1';
                                    /*if($insertU)
                                    {
                                    }
                                    else
                                    {
                                        if($msg==1){
                                            $msg == 'u01';
                                        }else{
                                            $msg = 'u0';
                                        }
                                    }*/

                                }

                            }
                        }
                    }
                }else{
        ##################################compiled processes starts##############################################################
                    $INNSER_QUERY_ALL = [];
                    foreach ($students as $student => $course_id) {
                        //$chk = $conn->query("SELECT * FROM spread_gp WHERE student_id='$student' ORDER BY id DESC LIMIT 1");
                        $sgp = \DB::table('spread_gp')->where('students_id', $student)->orderBy('id', 'DESC')->first();
                        if (!is_null($sgp)) {
                            //not first exam sheet for this student
                            //$sgp = $chk->fetch_assoc();
                            $NSS = $sgp->NSS + 1; 

                                //registered credit unit course {rcu}
                                $RCU = 0;
                                $ECU = 0;
                                $CP = 0;
                                $failedCourses ='';
                                foreach ($course_id as $key => $course) {
                                    /*$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
                                    $r = $rrcu->fetch_assoc();
                                    $RCU += $r['credit_unit'];*/
                                     $r = \DB::table('courses')->where('id', $course)->first();
                                    $RCU += $r->credit_unit;
                                    
                                    //geting students grades in this course
                                    $token_raw = $token.$r->course_code;

                                    $stR = \DB::table('results')->where(['result_token'=> $token_raw, 'students_id'=>$student, 'course_id'=>$course])->first();
                                    $stGrade = $stR->grades;

                                    if(in_array($stGrade, $failG_array)) {
                                        $failedCourses .= $r->course_code.', ';
                                        if($stGrade=='D') {
                                            $CP +=  $r->credit_unit *2;
                                        }elseif($stGrade=='E') {
                                            $CP +=  $r->credit_unit *1;
                                        }
                                    }else{
                                        $ECU += $r->credit_unit;
                                        if($stGrade=='A'){
                                            $CP +=  $r->credit_unit *5;
                                        }elseif($stGrade=='B') {
                                            $CP +=  $r->credit_unit *4;
                                        }elseif($stGrade=='C') {
                                            $CP +=  $r->credit_unit *3;
                                        }elseif($stGrade=='D') {
                                            $CP +=  $r->credit_unit *2;
                                        }elseif($stGrade=='E') {
                                            $CP +=  $r->credit_unit *1;
                                        }
                                    }

                                }
                                $GPA = number_format(($CP/$dcp) * 5, 2);
                                $TCP = $CP + $sgp->TCP;
                                //TDCP total department credit point
                                $TDCP = $sgp->TDCP +$dcp;
                                $CGPA = number_format(($TCP/$TDCP)*5,2);
                                $PCGPA = $sgp->CGPA;
                                $TRCU = $RCU + $sgp->TRCU;
                                $TECU = $ECU + $sgp->TECU;

                                //INSERT SPREAD SHEET IN DATABASE
                            $gt = \DB::table('students')->where('id', $student)->first();
                            $matric_number = $gt->matric_number;

                            /*$INNSER_QUERY_ALL[] = "('$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')";
*/
                            $INNSER_QUERY_ALL[] = [
                                                    'students_id'=> $student,
                                                    'NSS'=> $NSS,
                                                    'RCU'=> $RCU,
                                                    'ECU'=>$ECU,
                                                    'CP'=>$CP,
                                                    'GPA'=>$GPA,
                                                    'TRCU'=>$TRCU,
                                                    'TECU'=>$TECU,
                                                    'TCP'=>$TCP,
                                                    'TDCP'=>$TDCP,
                                                    'PCGPA'=>$PCGPA,
                                                    'CGPA'=>$CGPA,
                                                    'COs'=>$failedCourses,
                                                    'year'=>$session,
                                                    'semester'=>$semester,
                                                    'department_id'=>$department,
                                                    'level_id'=>$level,
                                                    'ncount' => '0'
                                                ];
                        }else{
                            //fist time exam student spread sheet
                            $NSS = 1;

                                //registered credit unit course {rcu}
                                $RCU = 0;
                                $ECU = 0;
                                $CP = 0;
                                $failedCourses ='';
                                foreach ($course_id as $key => $course) {
                                   
                                    $r = \DB::table('courses')->where('id', $course)->first();
                                    $RCU += $r->credit_unit;
                                    //echo $student ;
                                    //geting students grades in this course
                                    $token_raw = $token.$r->course_code;
                                    
                                    
                                    $stR = \DB::table('results')->where(['result_token'=> $token_raw, 'students_id'=>$student, 'course_id'=>$course])->first();
                                    $stGrade = $stR->grades;

                                    if(in_array($stGrade, $failG_array)) {
                                        $failedCourses .= $r->course_code.', ';
                                        if($stGrade=='D') {
                                            $CP +=  $r->credit_unit*2;
                                        }elseif($stGrade=='E') {
                                            $CP +=  $r->credit_unit*1;
                                        }
                                    }else{
                                        $ECU += $r->credit_unit;
                                        if($stGrade=='A'){
                                            $CP +=  $r->credit_unit*5;
                                        }elseif($stGrade=='B') {
                                            $CP +=  $r->credit_unit*4;
                                        }elseif($stGrade=='C') {
                                            $CP +=  $r->credit_unit*3;
                                        }elseif($stGrade=='D') {
                                            $CP +=  $r->credit_unit*2;
                                        }elseif($stGrade=='E') {
                                            $CP +=  $r->credit_unit*1;
                                        }
                                    }

                                }
                                $GPA = number_format(($CP/$dcp) * 5,2) ;
                                $TCP = $CP;
                                //TDCP total department credit point
                                $TDCP = $dcp;
                                $CGPA = $GPA;
                                $PCGPA = '-';
                                $TRCU = $RCU;
                                $TECU = $ECU;
                            //INSERT SPREAD SHEET IN DATABASE

                            $gt = \DB::table('students')->where('id', $student)->first();
                            $matric_number = $gt->matric_number;       
                            
                           /* $INNSER_QUERY_ALL[] = "('$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')";*/

                             $INNSER_QUERY_ALL[] = [
                                                    'students_id'=> $student,
                                                    'NSS'=> $NSS,
                                                    'RCU'=> $RCU,
                                                    'ECU'=>$ECU,
                                                    'CP'=>$CP,
                                                    'GPA'=>$GPA,
                                                    'TRCU'=>$TRCU,
                                                    'TECU'=>$TECU,
                                                    'TCP'=>$TCP,
                                                    'TDCP'=>$TDCP,
                                                    'PCGPA'=>$PCGPA,
                                                    'CGPA'=>$CGPA,
                                                    'COs'=>$failedCourses,
                                                    'year'=>$session,
                                                    'semester'=>$semester,
                                                    'department_id'=>$department,
                                                    'level_id'=>$level,
                                                    'ncount' => '0'
                                                ];
                        }
                    }
        //SAVE TO DATABASE WITH ONE QUERY`````````````````````````````````````````````````````````````````````````````````````````
                    //start transaction to sql{insert to two table once else rollback}
                    /*mysqli_query($conn, "SET AUTOCOMMIT=0");
                    mysqli_query($conn,"START TRANSACTION");*/

                   /* $INSERT = mysqli_query($conn, "INSERT INTO `spread_gp`(`student_id`, `NSS`, `RCU`, `ECU`, `CP`, `GPA`, `TRCU`, `TECU`, `TCP`, `TDCP`, `PCGPA`, `CGPA`, `COs`, `year`, `semester`, `department_id`, `level_id`, `ncount`) VALUES ".implode(',',$INNSER_QUERY_ALL)."");
                    echo mysqli_error($conn);   
                    \DB::table('semesters')->where('c_set',1)->first()->semester
                    */
                    //$compile_r = mysqli_query($conn, "INSERT INTO `compiled_r`(`id`, `session`, `semester`, `level`, `department`) VALUES (null,'$session', '$semester', '$department', '$level')");
                         $compile = new \App\compiled_r;
                         //$compile->id = null;
                         $compile->session = $session;
                         $compile->semester = $semester;
                         $compile->department = $department;
                         $compile->level = $level;
                         
                    if($compile->save()){
                        if(\App\spread_gp::insert($INNSER_QUERY_ALL)){
                            $trend = new \App\result_trend;

                            //$result_trend = $conn->query("INSERT INTO `result_trend`(`id`, `passFail`, `level`, `semester`, `session`, `department`, `status`) VALUES (null, '', '$level', '$semester', '$session', '$department',0)");
                            $trend->id = null;
                            $trend->passFail = '0,0';
                            $trend->level_id = $level;
                            $trend->semesters = $semester;
                            $trend->session = $session;
                            $trend->department = $department;
                            $trend->status = 0;
                            if($trend->save()) {
                                 return response()->json(['success'=>200]);
                            }
                        }
                    }

                    //write log
                    //get current semester
        //END SAVE TO DATABASE WITH ONE QUERY``````````````````````````````````````````````````````````````````````````````````````````
                }
                //END CHECK COMPILE
            }else{
                    //echo 000;
                    return response()->json(['success'=>207]);
                //return error to set grades
                }
                    
            }else{
                //alert error to set departmental TOTAL CREDIT UNIT
                return response()->json(['success'=>201]);
            }
/*
            if ($msg==1) {
                echo 1;
            }elseif($msg == 001){
                echo 'set current grading scale';
            }elseif($msg == 002){
                echo 'set departmental TOTAL CREDIT UNIT';
            }else*/
            if($msg=='u1'){
                //$result_trend = $conn->query("UPDATE result_trend SET status=0 WHERE level_id='$level' AND semesters='$semester' AND session='$session' AND department='$department'");
                $trend = \App\result_trend::where(['level_id'=>$level, 'semesters'=>$semester, 'session'=>$session, 'department'=>$department])->update(['status' => 0]);
                return response()->json(['success'=>400]);
            }elseif($msg=='ul'){
                return response()->json(['success'=>401]);
            }elseif($msg=='u0'){
                //echo 0;
            }


        //end porccess1
       
        //redirect()->route('processing_r');
    }
}
