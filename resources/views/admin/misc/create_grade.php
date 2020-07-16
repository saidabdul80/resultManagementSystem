<?php
//here will add new user{ insert into users and lecturers table}
session_start();
$userID = $_SESSION['user_id'];
include("../../php/connect.php");
	$gname = $_POST['gname'];
	$aa = $_POST['aa'];
	$bb = $_POST['bb'];
	$cc = $_POST['cc'];
	$dd = $_POST['dd'];
	$ee = $_POST['ee'];
	$ff = $_POST['ff'];
	$co = $_POST['co'];
	$date = date('m-d-Y');

	$chk = $conn->query("SELECT * FROM grades WHERE name='$gname'");
	if($chk->num_rows>0){
		echo 3;
	}else{
				$sql = "INSERT INTO `grades`(`name`, `A`, `B`, `C`, `D`, `E`, `F`,`CO`, `created_by`, `created_on`) VALUES (?,?,?,?,?,?,?,?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssssssssis',$gname, $aa, $bb,$cc,$dd,$ee,$ff,$co,$userID, $date);
				echo mysqli_error($conn);
				$status = $stmt->execute();
				if($status){
					echo 1;
				}else{
					echo 0;
				}
		}	

?>