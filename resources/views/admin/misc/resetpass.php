<?php
//here will add new user{ insert into users and lecturers table}
include("../../php/connect.php");
	//$email = $_POST['email'];
	$id = $_POST['id'];

	$chk = $conn->query("SELECT * FROM lecturers WHERE user_id='$id'");
	if($chk->num_rows>0){
		$rww = $chk->fetch_assoc();
		$pass = md5($rww['phone']);
		$sql = $conn->query("UPDATE users SET password='$pass' WHERE id='$id'");
		if($sql){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		echo 2;
	}
	

?>