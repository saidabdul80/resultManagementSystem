<?php
session_start();
$user_id =$_SESSION['user_id'];
include("../../php/connect.php");
$session = $_POST['session'];
$semester = $_POST['semester'];
$faculty = $_POST['faculty'];
$chk = $conn->query("SELECT * FROM f_timing WHERE session = '$session' AND semester='$semester' AND faculty='$faculty'");
if($chk->num_rows>0){
	$chk1 = $conn->query("SELECT * FROM f_timing WHERE session = '$session' AND semester='$semester' AND faculty='$faculty' AND status=0");
	if($chk1->num_rows>0){
		if($conn->query("UPDATE f_timing SET status=1 WHERE session = '$session' AND semester='$semester' AND faculty='$faculty'")){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		if($conn->query("UPDATE f_timing SET status=0 WHERE session = '$session' AND semester='$semester' AND faculty='$faculty'")){
			echo 1;
		}else{
			echo 0;
		}
	}

}else{
	$createT = $conn->query("INSERT INTO f_timing (`id`, `session`, `semester`,`faculty`, status) VALUES(null, '$session', '$semester', '$faculty', 0)");
	echo mysqli_error($conn);
	if($createT){
		echo 1;
	}else{
		0;
	}
}


?>