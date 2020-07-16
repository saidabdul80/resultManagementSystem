<?php
session_start();
if ($_POST['type']==1 && $_POST['type']!='') {
	unset($_SESSION['course_selected_id']);
	unset($_SESSION['course_update']);
}else if ($_POST['type']==2 && $_POST['type']!='') {
	unset($_SESSION['grade_selected_id']);
	unset($_SESSION['grade_update']);
}else{
	unset($_SESSION['selected_id']);
	unset($_SESSION['update']);
	unset($_SESSION['s_selected_id']);
	unset($_SESSION['s_update']);
}
?>