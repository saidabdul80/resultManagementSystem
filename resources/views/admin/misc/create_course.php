<?php
//here will add new user{ insert into users and lecturers table}
include("../../php/connect.php");
	$ctitle = $_POST['ctitle'];
	$ccode = $_POST['ccode'];
	$cdesc = $_POST['cdesc'];
	$dept = $_POST['dept'];
	$level = $_POST['level'];
	$cunit = $_POST['cunit'];
	$csemester = $_POST['csemester'];
	$date = date('m-d-Y');

	$chk = $conn->query("SELECT * FROM courses WHERE course_code='$ccode'");
	if($chk->num_rows>0){
		echo 3;
	}else{
		$chk = $conn->query("SELECT * FROM courses WHERE course_title ='$ctitle'");
		if($chk->num_rows>0){
			echo 4;
		}else{
				$sql = "INSERT INTO `courses`(`course_code`, `course_title`,credit_unit, `course_description`, `level_id`, `department_id`,`semester`, `created_on`) VALUES(?,?,?,?,?,?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssisiiis',$ccode, $ctitle,$cunit, $cdesc,$level,$dept,$csemester,$date);
				echo mysqli_error($conn);
				$status = $stmt->execute();
				if($status){
					echo 1;
				}else{
					echo 0;
				}
		}
	}
	

?>