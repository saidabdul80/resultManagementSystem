<?php
//here will add new user{ insert into users and lecturers table}
session_start();
$user_id = $_SESSION['user_id'];
include("../../php/connect.php");
	//$email = $_POST['email'];
	$cid = $_POST['csgid'];
	$gname = $_POST['gnm'];
	$oid = $_POST['ogid'];
	$date = date('Y-m-d');

	if ($oid=='') {
		//log comment
			$des = "<p>changes made on grading: ".$gname."  was set as current</p><p>by: ".$_SESSION['user_fullname']." with ID: ".$_SESSION['user_real_id']."</p>";
		$sqli = $conn->query("UPDATE grades SET c_set=0 WHERE id='$oid'");
		$sql = $conn->query("UPDATE grades SET c_set=1 WHERE id='$cid'");
		$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`, `description`, `action_date`) VALUES (null,'$user_id','$des','$date')");
		echo mysqli_error($conn);


	}else{
		//log comment
		$des = "\<p\>changes made on grading: ".$gname."  was set as current<\/p><p>by: ".$_SESSION['user_fullname']." with ID: ".$_SESSION['user_real_id']."<\/p>";
		$sqli = 1;
		$sql = $conn->query("UPDATE grades SET c_set=1 WHERE id='$cid'");
		$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`, `description`, `action_date`) VALUES (null,'$user_id','$des','$date')");
		echo mysqli_error($conn);

		

	}

		if($sql = $sqli){
			$_SESSION['current_set_grade_id'] = $cid;
			$_SESSION['current_set_name'] = $gname;
			echo 1;
		}else{
			echo 0;
		}
	

?>