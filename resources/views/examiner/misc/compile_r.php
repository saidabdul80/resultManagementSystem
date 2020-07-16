<?php
session_start();
$user_id =$_SESSION['user_id'];
include("../../php/connect.php");
$date = date('Y-m-d h:ia');
$level = $_POST['level'][0];
$allCourse = $_POST['allCourse'];
$semester = $_POST['semester'];
$type = $_POST['type'];
$session = $_POST['session'];
$department = $_POST['departmentID'];
$t_raw = explode('-', $_POST['token']);
$token = $t_raw[0].'-'.$t_raw[1].'-'.$t_raw[2].'-';
$year = $t_raw[0].'-'.$t_raw[1];
$msg = array();
//$token_raw = $_POST['token_raw'];
//echo sizeof($allCourse);

	
$fetch_all_students = $conn->query("SELECT * FROM students_registered_courses as sr INNER JOIN students as s ON sr.student_id=s.id WHERE s.department_id='$department' AND sr.session_id='$session' AND sr.semester='$semester' AND sr.level_id='$level' ORDER BY s.matric_number ASC");


$c =0;
$students = array();
$lastid = 0;
//echo mysqli_error($conn);
if($fetch_all_students->num_rows>0) {
	while ($fs = $fetch_all_students->fetch_assoc()) {
		$sid = $fs['student_id'];
		if($c == 0) {
			$students[$sid] = array();
			$students[$sid][] = $fs['course_id'];
			$lastid = $sid;
			$c++;
		}else{
			if($lastid==$sid) {
				$students[$sid][] = $fs['course_id'];
				$lastid = $sid;
			}else{
				$students[$sid] = array();
				$students[$sid][] = $fs['course_id'];
				$lastid = $sid;
			}
		}
	}	
}

$failG_array1 = array('D','E','F');
$failG_array2 = array('E','F');
$failG_array3 = array('F');

$cu = $conn->query("SELECT * FROM department_cu WHERE session_id='$session' AND semester='$semester'");
if ($cu->num_rows>0) {
	$cuu = $cu->fetch_assoc();

	//department cp
	$ll =$_POST['level'].'L';
	$CPP =explode('-', $cuu[$ll]);
	$dcup = $CPP[0];
	$dcp = $CPP[1];

	//get where CO starts from grades
	$getco = $conn->query("SELECT CO FROM grades WHERE c_set=1");
	if($getco->num_rows>0) {
		$sco = $getco->fetch_assoc();
		$co_start = $sco['CO'];
			
		if (in_array($co_start, $failG_array3)) {
			$failG_array = $failG_array3;
		}elseif(in_array($co_start, $failG_array2)) {
			$failG_array = $failG_array2;
		}elseif(in_array($co_start, $failG_array1)) {
			$failG_array = $failG_array1;
		}


#`````````````START CHECK compiled result for either session of semester ``````````

		$chk_compile = $conn->query("SELECT * FROM compiled_r WHERE session='$session' AND semester='$semester' AND level='$level' AND department='$department'");
		echo mysqli_error($conn);
		if($chk_compile->num_rows>0){
			if ($type==0) {
				echo 10001;
			}else{
				
#---------------UPDATE COMPILED RESULT IF RECOMPILING--/--/--/--/--/--/--/--/--/--/--/--/--/--/--/--/--/
##################################compiled processes starts###############################################
				$INNSER_QUERY_ALL = [];
				foreach ($students as $student => $course_id)
				{
					$chk = $conn->query("SELECT * FROM spread_gp WHERE student_id='$student' AND year!='$session' AND semester !='$semester' AND level_id != '$level' ORDER BY id DESC LIMIT 1");
					if ($chk->num_rows>0) {
						//not first exam sheet for this student

						//it checks if students result already compiled for this semester or not
						$chk1 = $conn->query("SELECT * FROM spread_gp WHERE student_id='$student' AND year='$session' AND semester ='$semester' AND level_id = '$level'");
						
						if ($chk1->num_rows>0) {
							#*******not a new student bt has not been compiled, now compile from recompiling stage***
							$sgp = $chk->fetch_assoc();
							$NSS = $sgp['NSS'] + 1;	

							//registered credit unit course {rcu}
							$RCU = 0;
							$ECU = 0;
							$CP = 0;
							$failedCourses ='';
							foreach ($course_id as $key => $course) {
								$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
								$r = $rrcu->fetch_assoc();
								$RCU += $r['credit_unit'];
									
								//geting students grades in this course
								$token_raw = $token.$r['course_code'];
								$getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
									
								$stR = $getG->fetch_assoc();
								$stGrade = $stR['grade'];
									
								if(in_array($stGrade, $failG_array)) {
								
									$failedCourses .= $r['course_code'].', ';
									if($stGrade=='D') {
										$CP +=  $r['credit_unit']*2;
									}elseif($stGrade=='E') {
										$CP +=  $r['credit_unit']*1;
									}
								
								}else{
								
									$ECU += $r['credit_unit'];
								
									if($stGrade=='A'){
										$CP +=  $r['credit_unit']*5;
									}elseif($stGrade=='B') {
										$CP +=  $r['credit_unit']*4;
									}elseif($stGrade=='C') {
										$CP +=  $r['credit_unit']*3;
									}elseif($stGrade=='D') {
										$CP +=  $r['credit_unit']*2;
									}elseif($stGrade=='E') {
										$CP +=  $r['credit_unit']*1;
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
							$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
							$gt = $getStudent->fetch_assoc();
							$matric_number = $gt['matric_number'];

							
							$UPDATE = $conn->query("UPDATE `spread_gp` SET  `RCU`='$RCU', `ECU`='$ECU', `CP`='$CP', `GPA`='$GPA', `TRCU`='$TRCU', `TECU`='$TECU', `TCP`='$TCP', `TDCP`='$TDCP', `PCGPA`='$PCGPA', `CGPA`='$CGPA', `COs`='$failedCourses' WHERE `student_id`='$student' AND `year`='$session' AND `semester`='$semester' AND `department_id`='$department' AND `level_id`='$level'");
							echo mysqli_error($conn);

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
							$sgp = $chk->fetch_assoc();
							$NSS = $sgp['NSS'] + 1;	

								//registered credit unit course {rcu}
								$RCU = 0;
								$ECU = 0;
								$CP = 0;
								$failedCourses ='';
								foreach ($course_id as $key => $course) {
									$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
									$r = $rrcu->fetch_assoc();
									$RCU += $r['credit_unit'];
									
									//geting students grades in this course
									$token_raw = $token.$r['course_code'];
									$getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
									
									$stR = $getG->fetch_assoc();
									$stGrade = $stR['grade'];
									
									if(in_array($stGrade, $failG_array)) {
										$failedCourses .= $r['course_code'].', ';
										if($stGrade=='D') {
											$CP +=  $r['credit_unit']*2;
										}elseif($stGrade=='E') {
											$CP +=  $r['credit_unit']*1;
										}
									}else{
										$ECU += $r['credit_unit'];
										if($stGrade=='A'){
											$CP +=  $r['credit_unit']*5;
										}elseif($stGrade=='B') {
											$CP +=  $r['credit_unit']*4;
										}elseif($stGrade=='C') {
											$CP +=  $r['credit_unit']*3;
										}elseif($stGrade=='D') {
											$CP +=  $r['credit_unit']*2;
										}elseif($stGrade=='E') {
											$CP +=  $r['credit_unit']*1;
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
							$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
							$gt = $getStudent->fetch_assoc();
							$matric_number = $gt['matric_number'];
							$insertT = $conn->query("INSERT INTO `spread_gp`(`id`, `student_id`, `NSS`, `RCU`, `ECU`, `CP`, `GPA`, `TRCU`, `TECU`, `TCP`, `TDCP`, `PCGPA`, `CGPA`, `COs`, `year`, `semester`, `department_id`, `level_id`, `ncount`) VALUES(null, '$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')");
							//$INNSER_QUERY_ALL[] = "";

							#*******End
						}

						
					}else{
						$chk1 = $conn->query("SELECT * FROM spread_gp WHERE student_id='$student' AND year='$session' AND semester ='$semester' AND level_id = '$level'");
						if ($chk1->num_rows>0) {
							//fist time exam student spread sheet BUT RECOMPILING
							$NSS = 1;

							//registered credit unit course {rcu}
							$RCU = 0;
							$ECU = 0;
							$CP = 0;
							$failedCourses ='';
							foreach ($course_id as $key => $course) {
								$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
								$r = $rrcu->fetch_assoc();
								$RCU += $r['credit_unit'];
								//echo $student ;
								//geting students grades in this course
								$token_raw = $token.$r['course_code'];
								$getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
									
								$stR = $getG->fetch_assoc();
								$stGrade = $stR['grade'];
									
								if(in_array($stGrade, $failG_array)) {
									$failedCourses .= $r['course_code'].', ';
									if($stGrade=='D') {
										$CP +=  $r['credit_unit']*2;
									}elseif($stGrade=='E') {
										$CP +=  $r['credit_unit']*1;
									}
								}else{
									$ECU += $r['credit_unit'];
									if($stGrade=='A'){
										$CP +=  $r['credit_unit']*5;
									}elseif($stGrade=='B') {
										$CP +=  $r['credit_unit']*4;
									}elseif($stGrade=='C') {
										$CP +=  $r['credit_unit']*3;
									}elseif($stGrade=='D') {
										$CP +=  $r['credit_unit']*2;
									}elseif($stGrade=='E') {
										$CP +=  $r['credit_unit']*1;
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
							$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
							$gt = $getStudent->fetch_assoc();
							$matric_number = $gt['matric_number'];	
							/*$insertT = $conn->query("INSERT INTO `spread_gp`(`id`, `student_id`, `NSS`, `RCU`, `ECU`, `CP`, `GPA`, `TRCU`, `TECU`, `TCP`, `TDCP`, `PCGPA`, `CGPA`, `COs`, `year`, `semester`, `department_id`, `level_id`, `ncount`) VALUES(null, '$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')");/*				*/
							$UPDATE = $conn->query("UPDATE `spread_gp` SET  `NSS`='$NSS', `RCU`='$RCU', `ECU`='$ECU', `CP`='$CP', `GPA`='$GPA', `TRCU`='$TRCU', `TECU`='$TECU', `TCP`='$TCP', `TDCP`='$TDCP', `PCGPA`='$PCGPA', `CGPA`='$CGPA', `COs`='$failedCourses' WHERE `student_id`='$student' AND `year`='$session' AND `semester`='$semester' AND `department_id`='$department' AND `level_id`='$level'");
							echo mysqli_error($conn);

							if($UPDATE)
							{
								$msg = 'u1';
							}
							else
							{
								if($msg==1){
									$msg == 'u01';
								}else{
									$msg = 'u0';
								}
							}
						}else{
							//fist time exam student spread sheet
							$NSS = 1;

							//registered credit unit course {rcu}
							$RCU = 0;
							$ECU = 0;
							$CP = 0;
							$failedCourses ='';
							foreach ($course_id as $key => $course) {
								$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
								$r = $rrcu->fetch_assoc();
								$RCU += $r['credit_unit'];
								//echo $student ;
								//geting students grades in this course
								$token_raw = $token.$r['course_code'];
								$getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
									
								$stR = $getG->fetch_assoc();
								$stGrade = $stR['grade'];
									
								if(in_array($stGrade, $failG_array)) {
									$failedCourses .= $r['course_code'].', ';
									if($stGrade=='D') {
										$CP +=  $r['credit_unit']*2;
									}elseif($stGrade=='E') {
										$CP +=  $r['credit_unit']*1;
									}
								}else{
									$ECU += $r['credit_unit'];
									if($stGrade=='A'){
										$CP +=  $r['credit_unit']*5;
									}elseif($stGrade=='B') {
										$CP +=  $r['credit_unit']*4;
									}elseif($stGrade=='C') {
										$CP +=  $r['credit_unit']*3;
									}elseif($stGrade=='D') {
										$CP +=  $r['credit_unit']*2;
									}elseif($stGrade=='E') {
										$CP +=  $r['credit_unit']*1;
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
							$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
							$gt = $getStudent->fetch_assoc();
							$matric_number = $gt['matric_number'];	
							$insertU = $conn->query("INSERT INTO `spread_gp`(`id`, `student_id`, `NSS`, `RCU`, `ECU`, `CP`, `GPA`, `TRCU`, `TECU`, `TCP`, `TDCP`, `PCGPA`, `CGPA`, `COs`, `year`, `semester`, `department_id`, `level_id`, `ncount`) VALUES(null, '$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')");/*				
							$UPDATE = $conn->query("UPDATE `spread_gp` SET  `NSS`='$NSS', `RCU`='$RCU', `ECU`='$ECU', `CP`='$CP', `GPA`='$GPA', `TRCU`='$TRCU', `TECU`='$TECU', `TCP`='$TCP', `TDCP`='$TDCP', `PCGPA`='$PCGPA', `CGPA`='$CGPA', `COs`='$failedCourses' WHERE `student_id`='$student' AND `year`='$session' AND `semester`='$semester' AND `department_id`='$department' AND `level_id`='$level'");*/
							echo mysqli_error($conn);

							if($insertU)
							{
								$msg = 'u1';
							}
							else
							{
								if($msg==1){
									$msg == 'u01';
								}else{
									$msg = 'u0';
								}
							}

						}

					}
				}
			}
		}else{
##################################compiled processes starts##############################################################
			$INNSER_QUERY_ALL = [];
			foreach ($students as $student => $course_id) {
				$chk = $conn->query("SELECT * FROM spread_gp WHERE student_id='$student' ORDER BY id DESC LIMIT 1");
				if ($chk->num_rows>0) {
					//not first exam sheet for this student
					$sgp = $chk->fetch_assoc();
					$NSS = $sgp['NSS'] + 1;	

						//registered credit unit course {rcu}
						$RCU = 0;
						$ECU = 0;
						$CP = 0;
						$failedCourses ='';
						foreach ($course_id as $key => $course) {
							$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
							$r = $rrcu->fetch_assoc();
							$RCU += $r['credit_unit'];
							
							//geting students grades in this course
							$token_raw = $token.$r['course_code'];
							$getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
							
							$stR = $getG->fetch_assoc();
							$stGrade = $stR['grade'];
							
							if(in_array($stGrade, $failG_array)) {
								$failedCourses .= $r['course_code'].', ';
								if($stGrade=='D') {
									$CP +=  $r['credit_unit']*2;
								}elseif($stGrade=='E') {
									$CP +=  $r['credit_unit']*1;
								}
							}else{
								$ECU += $r['credit_unit'];
								if($stGrade=='A'){
									$CP +=  $r['credit_unit']*5;
								}elseif($stGrade=='B') {
									$CP +=  $r['credit_unit']*4;
								}elseif($stGrade=='C') {
									$CP +=  $r['credit_unit']*3;
								}elseif($stGrade=='D') {
									$CP +=  $r['credit_unit']*2;
								}elseif($stGrade=='E') {
									$CP +=  $r['credit_unit']*1;
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
					$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
					$gt = $getStudent->fetch_assoc();
					$matric_number = $gt['matric_number'];

					$INNSER_QUERY_ALL[] = "('$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')";
				}else{
					//fist time exam student spread sheet
					$NSS = 1;

						//registered credit unit course {rcu}
						$RCU = 0;
						$ECU = 0;
						$CP = 0;
						$failedCourses ='';
						foreach ($course_id as $key => $course) {
							$rrcu = $conn->query("SELECT credit_unit, course_code FROM courses WHERE id ='$course'");
							$r = $rrcu->fetch_assoc();
							$RCU += $r['credit_unit'];
							//echo $student ;
							//geting students grades in this course
							 $token_raw = $token.$r['course_code'];
							$getG = $conn->query("SELECT * FROM results WHERE result_token='$token_raw' AND students_id='$student' AND course_id='$course'");
							
							$stR = $getG->fetch_assoc();
							$stGrade = $stR['grade'];
							
							if(in_array($stGrade, $failG_array)) {
								$failedCourses .= $r['course_code'].', ';
								if($stGrade=='D') {
									$CP +=  $r['credit_unit']*2;
								}elseif($stGrade=='E') {
									$CP +=  $r['credit_unit']*1;
								}
							}else{
								$ECU += $r['credit_unit'];
								if($stGrade=='A'){
									$CP +=  $r['credit_unit']*5;
								}elseif($stGrade=='B') {
									$CP +=  $r['credit_unit']*4;
								}elseif($stGrade=='C') {
									$CP +=  $r['credit_unit']*3;
								}elseif($stGrade=='D') {
									$CP +=  $r['credit_unit']*2;
								}elseif($stGrade=='E') {
									$CP +=  $r['credit_unit']*1;
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
					$getStudent = $conn->query("SELECT matric_number from students WHERE id='$student'");
					$gt = $getStudent->fetch_assoc();
					$matric_number = $gt['matric_number'];							
					$INNSER_QUERY_ALL[] = "('$student', '$NSS', '$RCU', '$ECU','$CP', '$GPA','$TRCU','$TECU','$TCP', '$TDCP','$PCGPA','$CGPA','$failedCourses','$session','$semester','$department', '$level','0')";
				}
			}
//SAVE TO DATABASE WITH ONE QUERY`````````````````````````````````````````````````````````````````````````````````````````
			//start transaction to sql{insert to two table once else rollback}
			mysqli_query($conn, "SET AUTOCOMMIT=0");
			mysqli_query($conn,"START TRANSACTION");

			$INSERT = mysqli_query($conn, "INSERT INTO `spread_gp`(`student_id`, `NSS`, `RCU`, `ECU`, `CP`, `GPA`, `TRCU`, `TECU`, `TCP`, `TDCP`, `PCGPA`, `CGPA`, `COs`, `year`, `semester`, `department_id`, `level_id`, `ncount`) VALUES ".implode(',',$INNSER_QUERY_ALL)."");
			echo mysqli_error($conn);	

			$compile_r = mysqli_query($conn, "INSERT INTO `compiled_r`(`id`, `session`, `semester`, `level`, `department`) VALUES (null,'$session', '$semester', '$department', '$level')");

			//write log
			if ($_SESSION['current_set_semester']==1) {
				$csemestern = 'first semester';
			}else{ $csemestern = 'second semester'; }
			$des = "<p>Level ".$level." ".$_SESSION['current_set_session']." ".$csemestern." <span class=\'badge badge-success\'>Result compiled</span> by ".$_SESSION['user_fullname']." (".$_SESSION['user_real_id'].")</p>";

			if($INSERT && $compile_r) {
				$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`,`type`, `description`, `action_date`) VALUES (null,'$user_id','examiner','$des','$date')");
				$result_trend = $conn->query("INSERT INTO `result_trend`(`id`, `passFail`, `level`, `semester`, `session`, `department`, `status`) VALUES (null, '', '$level', '$semester', '$session', '$department',0)");
				echo mysqli_error($conn);
			    mysqli_query($conn,"COMMIT");
			    $msg = 1;
			} else {
			    mysqli_query($conn,"ROLLBACK");
			}
			mysqli_query($conn, "SET AUTOCOMMIT=1");
//END SAVE TO DATABASE WITH ONE QUERY``````````````````````````````````````````````````````````````````````````````````````````
		}
		//END CHECK COMPILE
	}else{
			//echo 000;
			$msg = 001;
		//return error to set grades
		}
			
	}else{
			//alert error to set departmental TOTAL CREDIT UNIT
		$msg = 002;
	}

	if ($msg==1) {
		echo 1;
	}elseif($msg == 001){
		echo 'set current grading scale';
	}elseif($msg == 002){
		echo 'set departmental TOTAL CREDIT UNIT';
	}elseif($msg=='u1'){
		$result_trend = $conn->query("UPDATE result_trend SET status=0 WHERE level='$level' AND semester='$semester' AND session='$session' AND department='$department'");
		echo 1;
	}elseif($msg=='ul'){
		echo 'please recompile: not all successfully saved after compiling';
	}elseif($msg=='u0'){
		echo 0;
	}
