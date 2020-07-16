<?php
//here will add new user{ insert into users and lecturers table}
include("../../php/connect.php");
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
	$chk = $conn->query("SELECT * FROM students WHERE matric_number='$matric'");
	if($chk->num_rows>0){
		echo 3;
	}else{
		$chk = $conn->query("SELECT * FROM students WHERE email='$email'");
		if($chk->num_rows>0){
			echo 4;
		}else{
				$sql = "INSERT INTO `students`( `first_name`, `surname`, `other_name`, `matric_number`, `phone_number`, `email`, `country`, `state_of_origin`, `lga`, `address`, `nxt_of_kin_name`, `nxt_of_kin_phone`, `nxt_of_kin_address`, `department_id`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('sssssssssssssi',$first_name, $surname, $other_name,$matric, $phone, $email, $country, $state, $nkn, $nkp, $nka,$address,$lga,$deptID);
				echo mysqli_error($conn);
				$status = $stmt->execute();
				if($status){
					echo 1;
				}else{
					echo 0;
				}
		}
	}
	

?>