<?php
session_start();
$user_id =$_SESSION['user_id'];
include("../../php/connect.php");
$rid = $_POST['rid'];
$uid = $_POST['uid'];
$rname = $_POST['rname'];
$date = date('Y-m-d');

$runc = $conn->query("SELECT * FROM users as u INNER JOIN lecturers as l ON l.email=u.username WHERE username='$uid'");
if($runc->num_rows>0){
	$fet = $runc->fetch_assoc();
	$frole = explode(',', $fet['role_id']);
	$actionUser = $fet['first_name'].' '.$fet['surname'];
	$actionID = $fet['lecture_ID'];
	//echo $roles = str_replace(','.$rid,'', $frole);
	$c  =0;
	$roles ='';
	foreach ($frole as $key => $vl) {
		if ($rid==$vl) {
			
		}else{
			if ($c==0) {
				$c++;
				$roles .=  $vl;
			}else{
				$roles .=','.$vl;
			}
		}
	}
	if ($roles=='') {
		$roles = 5;
	}
	$run = $conn->query("UPDATE users SET role_id='$roles' WHERE username = '$uid'");
	//create log
	$des = "<p>".$rname." Role Deassign from ".$actionUser." (".$actionID.") By ".$_SESSION['user_fullname']." (".$_SESSION['user_real_id'].") On ".$date."</p>";
	$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`, `description`, `action_date`) VALUES (null,'$user_id','$des','$date')");
	//end;
		if(!$run){
			echo 0;
		}else{
			echo 1;
		}

}
?>