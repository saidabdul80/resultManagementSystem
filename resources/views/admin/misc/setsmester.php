<?php
include("../../php/connect.php");

	session_start();
	$user_id = $_SESSION['user_id'];
	$date = date('Y-m-d');

	$id = $_POST['id'];
	if ($id==1) {
		$semesterchange = 'first semester';
	}else{
		$semesterchange = 'second semester';
	}

	$sql0 = "UPDATE semesters SET c_set=0";
	$run0 = $conn->query($sql0);
	$sql1 = "UPDATE semesters SET c_set=1 WHERE semester_id='$id' ";
	
	$run1 = $conn->query($sql1);
	if ($run1 == $run0) {
		$_SESSION['current_set_semester_id'] = $id;
		$_SESSION['current_set_semester']    = $_POST['name'];
		//log comment
			$des = "\<p\>Semester was set from: ".$semesterchange." to ".$_POST['name']."<\/p><p>by: ".$_SESSION['user_fullname']." with ID: ".$_SESSION['user_real_id']."<\/p>";
		$sqli = 1;
		$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`, `description`, `action_date`) VALUES (null,'$user_id','$des','$date')");
		echo mysqli_error($conn);
		echo 1;
	}else{
		echo 0;
	}

?>