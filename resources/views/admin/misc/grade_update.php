<?php
//here will add new user{ insert into users and lecturers table}
session_start();
$userID = $_SESSION['user_id'];
include("../../php/connect.php");
	$gname = $_POST['gname'];
	$id = $_POST['id'];
	$aa = $_POST['aa'];
	$bb = $_POST['bb'];
	$cc = $_POST['cc'];
	$dd = $_POST['dd'];
	$ee = $_POST['ee'];
	$ff = $_POST['ff'];
	$co = $_POST['co'];
	$date = date('m-d-Y');

				$sql = "UPDATE `grades`  SET `name`=?,`A`=?,`B`=?,`C`=?,`D`=?,`E`=?,`F`=?,`CO`=? WHERE id='$id'";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssssssss',$gname, $aa, $bb,$cc,$dd,$ee,$ff,$co);
				echo mysqli_error($conn);
				$status = $stmt->execute();
				if($status){
					echo 1;
				}else{
					echo 0;
				}	

?>