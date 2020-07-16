<?php
//here will add new user{ insert into users and lecturers table}
session_start();
include("../../php/connect.php");
	$selid = $_SESSION['s_selected_id'];
	$matric = $_POST['matric'];
	$first_name = $_POST['first_name'];
	$surname = $_POST['surname'];
	$other_name = $_POST['other_name'];
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
	
				$sql = "UPDATE `students` SET `first_name`=? , `surname`=?,`other_name`=? , `matric_number`=?, `phone_number`=?, `email`=?, `country`=?, `state_of_origin`=?, `nxt_of_kin_name`=?, `nxt_of_kin_phone`=?, `nxt_of_kin_address`=?, `address`=?, `lga`=?, `department_id`=? WHERE id = '$selid'";
				$stmt = $conn->prepare($sql);
				echo mysqli_error($conn);
				$stmt->bind_param('sssssssssssssi', $first_name, $surname,$other_name, $matric, $phone, $email, $country, $state, $nkn, $nkp, $nka,$address,$lga,$deptID);
				$status = $stmt->execute();
				if($status){
					echo 1;
				}else{
					echo 0;
				}
			

	

?>