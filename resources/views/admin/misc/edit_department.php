<?php
include("../../php/connect.php");

$department = $_POST['department'];
$abbr    = $_POST['dabbr'];
$id    = $_POST['id'];

if(preg_match('/[¬`\|<>£\$%\^\+=]/', $department)){
//if(preg_match('/["\'¬`\|<>£\$%\^\[\]&\(\)-_\+=\{\}\?]/', $session)){
	echo 3;
}else{
			$runc = $conn->query("SELECT * FROM departments WHERE department='$department' AND department_abbr ='$abbr'");
			if($runc->num_rows>0){
				echo 2;
			}else{
				$sql ="UPDATE `departments` SET `department` =? , `department_abbr`=? WHERE id='$id'";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ss',$department,$abbr);
				$status = $stmt->execute();
				if(!$status){
					echo 0;
				}else{
					echo 1;
				}
		}
}

?>