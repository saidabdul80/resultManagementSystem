<?php
session_start();
$user_id =$_SESSION['user_id'];
include("../../php/connect.php");
$passFail = $_POST['pf'];
$level = $_POST['level'];
$semester = $_POST['semester'];
$session = $_POST['session'];
$department = $_POST['department'];

$save = $conn->query("UPDATE `result_trend` SET `passFail`='$passFail' ,`status`=1 WHERE `level`='$level' AND `semester`='$semester' AND`session`='$session' AND `department`= '$department'");

if ($save) {
	echo 1;
}else{
	echo 0;
}




?>