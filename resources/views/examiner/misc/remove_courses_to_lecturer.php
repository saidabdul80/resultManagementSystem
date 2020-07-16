<?php
session_start();
$user_id =$_SESSION['user_id'];
include("../../php/connect.php");
$courses = $_POST['subArr'];
$lectid = $_POST['lec_id'];
$date = date('Y-m-d h:ia');
$result = 0;
$exist =0;
$exists ='';

	foreach ($courses as $key => $value) {
			$runc =$conn->query("DELETE FROM lecturer_allocated_courses WHERE id = '$value'");
				if($runc) {
					$result = 1;
				}else{
					$result = 0;
				}
		}
	if ($result==1) {
		echo "Successuful";
	}else{
		if ($exists ==0) {
			echo 0;
		}
	}
?>