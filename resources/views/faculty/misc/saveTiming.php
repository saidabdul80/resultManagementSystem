<?php
session_start();
$user_id =$_SESSION['user_id'];
include("../../php/connect.php");
$date = date('Y-m-d h:ia');
$session = $_POST['session'];
$semester = $_POST['semester'];
$faculty = $_POST['faculty'];
$tim1 = $_POST['tim1'];
$tim2 = $_POST['tim2'];
$csemestern = ($semester==1)? 'first semester' :'second semester';
$des = "<p>Level ".$_SESSION['faculty']." ".$_SESSION['current_set_session']." ".$csemestern." <span class=\'badge badge-success\'>Result upload Timing new setting between ".$tim1." to ".$tim2." </span> by ".$_SESSION['user_fullname']." (".$_SESSION['user_real_id'].")</p>";

$createT = $conn->query("UPDATE f_timing SET startsT = '$tim1', endT='$tim2', status=1 WHERE session = '$session' AND semester='$semester' AND faculty='$faculty'");

echo mysqli_error($conn);
$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`,`type`, `description`, `action_date`) VALUES (null,'$user_id','faculty','$des','$date')");
if($createT && $lg) {
	mysqli_query($conn,"COMMIT");
	echo 1;
} else {
    mysqli_query($conn,"ROLLBACK");
    echo 0;
}
mysqli_query($conn, "SET AUTOCOMMIT=1");


?>