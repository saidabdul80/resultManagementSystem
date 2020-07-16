<?php
session_start();
$user_id =$_SESSION['user_id'];
$session_id = $_SESSION['current_set_session_id'];
include("../../php/connect.php");
$courses = $_POST['subArr'];
$lectid = $_POST['lec_id'];
$dept = $_POST['dept'];
$date = date('Y-m-d h:ia');
$result = 0;
$exist =0;
$exists ='';
$coursenames ='';
$actionID ='';

	if ($session_id!='') {
		foreach ($courses as $key => $value) {
			$chk =$conn->query("SELECT * FROM lecturer_allocated_courses AS l INNER JOIN courses as c ON c.id=l.course_id  INNER JOIN users AS u ON u.id='$lectid' INNER JOIN lecturers as le ON le.email=u.username  WHERE l.course_id='$value' AND l.session_id ='$session_id' AND l.department_id='$dept'");
			echo mysqli_error($conn);
			if ($chk->num_rows>0){
				$exist = 3;
				$sel = $chk->fetch_assoc();
				$exists .= $sel['course_code'].', ';
				$actionID .= $sel['lecture_ID'].', ';
			}else{
				$chk1 = $conn->query("SELECT * FROM courses WHERE id='$value'");
				$sel1 = $chk1->fetch_assoc();
				$coursenames .= $sel1['course_code'].', ';
				$runc = $conn->query("INSERT INTO `lecturer_allocated_courses`(`lecturer_id`, `course_id`, `session_id`,`department_id`, `created_by_user_id`, `created_on`) VALUES ('$lectid','$value','$session_id','$dept','$user_id','$date')");
				if($runc) {
					$result = 1;
				}else{
					//$result = 0;
				}
		}
	}
	if ($result==1) {
		$actionu = $conn->query("SELECT * FROM users as u INNER JOIN lecturers as le ON le.email=u.username WHERE u.id='$lectid' ");
		$sel1 = $actionu->fetch_assoc();
		$actionUser = $sel1['first_name'].' '.$sel1['surname'];
		$actionID = $sel1['lecture_ID'];

		//create log
		$des = "<p>".$coursenames."  Assigned to ".$actionUser." (".$actionID.") By ".$_SESSION['user_fullname']." (".$_SESSION['user_real_id'].") On ".$date."</p>";
		$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`, `description`, `action_date`) VALUES (null,'$user_id','$des','$date')");
		echo "Successuful";
	//end;
	}else{
		if ($exists ==0) {
			echo 0;
		}
	}
	if($exist !=0){
		echo $exists." already exists";
	}
}else{
	echo "please set session";
}
?>