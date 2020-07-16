<?php
session_start();
if ($_POST['type']==1 && $_POST['type']!='') {
	$update = $_POST['update'];
	$id = $_POST['id'];
	$_SESSION['course_selected_id'] = $id;
	$_SESSION['course_update']      = $update;
}else if ($_POST['type']==2 && $_POST['type']!='') {
	$update = $_POST['update'];
	$id = $_POST['id'];
	$_SESSION['grade_selected_id'] = $id;
	$_SESSION['grade_update']      = $update;
}else{
	$update = $_POST['update'];
	$id = $_POST['id'];
	$_SESSION['s_selected_id'] = $id;
	$_SESSION['s_update']      = $update;
}


?>