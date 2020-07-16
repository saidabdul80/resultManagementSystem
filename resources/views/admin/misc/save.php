<?php
//here will add new user{ insert into users and lecturers table}
include("../../php/connect.php");
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
	$chk = $conn->query("SELECT * FROM lecturers WHERE lecture_ID='$lecturerID'");
	if($chk->num_rows>0){
		echo 3;
	}else{
		$chk = $conn->query("SELECT * FROM lecturers WHERE email='$email'");
		if($chk->num_rows>0){
			echo 4;
		}else{
			$uinsert = "INSERT INTO `users`(`username`, `password`,role_id) VALUES(?,?,?)";
			$stmt1 = $conn->prepare($uinsert);
				echo mysqli_error($conn);
			$role = 5;
			$pass =md5($phone);
			$stmt1->bind_param('ssi', $email, $pass,$role);
			$status1 = $stmt1->execute();
			if($status1){
				$user_id = $conn->insert_id;
				$sql = "INSERT INTO `lecturers`( `first_name`, `surname`, `lecture_ID`, `phone`, `email`, `country`, `state`, `nxt_of_kin_name`, `nxt_of_kin_phone`, `nxt_of_kin_address`, `address`, `lga`, `department_id`, `user_id`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssssssssssssii', $first_name, $surname, $lecturerID, $phone, $email, $country, $state, $nkn, $nkp, $nka,$address,$lga,$deptID,$user_id);
				$status = $stmt->execute();
				if($status){
					echo 1;
				}else{
					echo 0;
				}
			}else{
				echo 0;
			}
		}
	}
	

?>