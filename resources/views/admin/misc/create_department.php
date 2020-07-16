<?php
include("../../php/connect.php");

$ndepartment = explode(',',$_POST['ndepartment']);
$department = $ndepartment[0];
$abbr    = $ndepartment[1];
$fid    = $_POST['fid'];

if(preg_match('/[¬`\|<>£\$%\^\+=]/', $department)){
//if(preg_match('/["\'¬`\|<>£\$%\^\[\]&\(\)-_\+=\{\}\?]/', $session)){
	echo 3;
}else{
			$runc = $conn->query("SELECT * FROM departments WHERE department='$department'");
			if($runc->num_rows>0){
				echo 2;
			}else{
				$sql ="INSERT INTO `departments`(`department`, `department_abbr`, `faculty_id`) VALUES(?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssi',$department,$abbr,$fid);
				$status = $stmt->execute();
				if(!$status){
					echo 0;
				}else{
					echo 1;
				}
		}
}

?>