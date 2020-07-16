<?php
session_start();
$user_id =$_SESSION['user_id'];
include("../../php/connect.php");
$token_raw = $_POST['token_raw'];
$type = $_POST['type'];
$from = $_POST['from'];
//$textMsg = $_POST['textMsg'];
$piece = explode('-', $token_raw);
$session = $piece[0].'-'.$piece[1];
$semester = $piece[2];
$course = $piece[3];

$date = date('Y-m-d h:ia');

//get lecture of a course
/*$ll = $conn->query("SELECT *, u.id as uid FROM courses as c INNER JOIN lecturer_allocated_courses as lc ON lc.course_id=c.id INNER JOIN users as u ON u.id=lc.lecturer_id WHERE c.course_code='$course'");
$l = $ll->fetch_assoc();
$lecturer_of_a_course_id = $l['uid'];*/

if($type=='accept') {
	//update result file for examiner
	$run_update = $conn->query("UPDATE result_files SET result_confirm_by_lect=1 WHERE result_token='$token_raw'");
	echo mysqli_error($conn);
	//send message
/*	$inser_msg = $conn->query("INSERT INTO `messages`(`id`, `message`, `from_user_id`, `to_user_id`, `state`, `date_sent`) VALUES(null,'Result has been Accepted from the exams office', '$user_id','$lecturer_of_a_course_id', 0, '$date')");
*/
	//prepare logs details
	$des = "<p>".strtoupper($course)." ".$session." ". $semester."<span class=\'badge badge-success\'> Result Confirmed</span> by ".$_SESSION['user_fullname']." (".$_SESSION['user_real_id'].") On ".$date."</p>";
	//insert logs
	$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`,`type`, `description`, `action_date`) VALUES (null,'$user_id','lecturer','$des','$date')");
	echo mysqli_error($conn);

	if ($run_update==$lg) {
		echo 1;
	}else{
		echo 0;
	}
}elseif($type=='delete'){
	//update result file for examiner
	$run_update = $conn->query("DELETE FROM result_files WHERE result_token='$token_raw'");
	$run_update = $conn->query("DELETE FROM results WHERE result_token='$token_raw'");
	$files = glob('../uploads/'.$token_raw.'.*');
	if (count($files)>0) {
		if(is_array($files)){
			foreach ($files as $value) {
				unlink($value);
			}
		}else{
			unlink($files);
		}
	}

	//unlink('../uploads/'.$token_raw)
	//echo 444;


	//prepare logs details
	$des = "<p>".strtoupper($course)." ".$session." ". $semester."<span class=\'badge badge-danger\'> Result Deleted</span> by ".$_SESSION['user_fullname']." (".$_SESSION['user_real_id'].") On ".$date."</p>";
	//insert logs
		$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`,`type`, `description`, `action_date`) VALUES (null,'$user_id','lecturer','$des','$date')");
	if ($run_update==$lg) {
		echo 1;
	}else{
		echo 0;
	}
}
?>