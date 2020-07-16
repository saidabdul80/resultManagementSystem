<?php
//here will add new user{ insert into users and lecturers table}
include("../../php/connect.php");
	$matric = $_POST['nmatric'];
	$id = $_POST['id'];

	$chk = $conn->query("SELECT * FROM students WHERE matric_number='$matric'");
	if($chk->num_rows>0){
		echo 2;
	}else{
		$sql = $conn->query("UPDATE students SET matric_number='$matric' WHERE id='$id'");
		if($sql){
			echo 1;
		}else{
			echo 0;
		}
	}
	

?>