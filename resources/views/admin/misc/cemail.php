<?php
//here will add new user{ insert into users and lecturers table}
include("../../php/connect.php");
	$email = $_POST['email'];
	$id = $_POST['id'];

	$chk = $conn->query("SELECT * FROM lecturers WHERE email='$email'");
	if($chk->num_rows>0){
		echo 2;
	}else{
		$sql = $conn->query("UPDATE users SET username='$email' WHERE id='$id'");
		$sql1 = $conn->query("UPDATE lecturers SET email='$email' WHERE user_id='$id'");
		if($sql == $sql1){
			echo 1;
		}else{
			echo 0;
		}
	}
	

?>