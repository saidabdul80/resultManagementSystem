<?php
session_start();
if ($_POST['type']==1 && $_POST['type']!='') {
	$selectedC = $_POST['selected'];
	$id = $_POST['id'];
	$_SESSION['selected_course_id']  = $id;
	$_SESSION['selected_course_code']  = $_POST['ccode'];
}else if ($_POST['type']==2 && $_POST['type']!='') {
	
}else{
	
}


?>