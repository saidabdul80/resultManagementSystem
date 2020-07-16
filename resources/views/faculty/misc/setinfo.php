<?php
session_start();
if ($_POST['type']==1 && $_POST['type']!='') {
	$allocourse = $_POST['allocourse'];
	$id = $_POST['id'];
	$_SESSION['sel_lect_id'] = $id;
	$_SESSION['allocourse']  = $allocourse;
	$_SESSION['lectID']  = $_POST['lectID'];
	$_SESSION['fname']  = $_POST['fname'];
}else if ($_POST['type']==2 && $_POST['type']!='') {
	$update = $_POST['update'];
	$id = $_POST['id'];
	$_SESSION['grade_selected_id'] = $id;
	$_SESSION['grade_update']      = $update;
}else if($_POST['type']==3 && $_POST['type']!=''){
	$selectedC = $_POST['selected'];
	$id = $_POST['id'];
	$_SESSION['eselected_course_id']  = $id;
	$_SESSION['eselected_course_code']  = $_POST['ccode'];
	$_SESSION['efaculty']  = $_POST['faculty'];
	$_SESSION['edepartment']  = $_POST['department'];
}else{
	$update = $_POST['update'];
	$id = $_POST['id'];
	$_SESSION['s_selected_id'] = $id;
	$_SESSION['s_update']      = $update;
}


?>