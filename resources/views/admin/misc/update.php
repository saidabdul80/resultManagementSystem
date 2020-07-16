<?php
//here will add new user{ insert into users and lecturers table}
session_start();
include("../../php/connect.php");
	$selid = $_SESSION['selected_id'];
	$lecturerID = $_POST['lecturerID'];
	$first_name = $_POST['first_name'];
	$surname = $_POST['surname'];
	$deptID = $_POST['deptID'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$lga = $_POST['lga'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$nkn = $_POST['nkn'];
	$nka = $_POST['nka'];
	$nkp = $_POST['nkp'];
	
				$sql = "UPDATE `lecturers` SET `first_name`=? , `surname`=?, `lecture_ID`=?, `phone`=?, `email`=?, `country`=?, `state`=?, `nxt_of_kin_name`=?, `nxt_of_kin_phone`=?, `nxt_of_kin_address`=?, `address`=?, `lga`=?, `department_id`=? WHERE user_id = '$selid'";
				$stmt = $conn->prepare($sql);
				echo mysqli_error($conn);
				$stmt->bind_param('ssssssssssssi', $first_name, $surname, $lecturerID, $phone, $email, $country, $state, $nkn, $nkp, $nka,$address,$lga,$deptID);
				$status = $stmt->execute();
				if($status){
					echo 1;
				}else{
					echo 0;
				}
			

	

?>