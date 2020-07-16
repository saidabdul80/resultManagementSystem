<?php
//here will add new user{ insert into users and lecturers table}
include("../../php/connect.php");
	$nlectID = $_POST['nlectID'];
	$id = $_POST['id'];

	$chk = $conn->query("SELECT * FROM lecturers WHERE lecture_ID='$nlectID'");
	if($chk->num_rows>0){
		echo 2;
	}else{
		$sql = $conn->query("UPDATE lecturers SET lecture_ID='$nlectID' WHERE user_id='$id'");
		if($sql){
			echo 1;
		}else{
			echo 0;
		}
	}
	

?>