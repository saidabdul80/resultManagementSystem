<?php
include("../../php/connect.php");


$faculty = $_POST['faculty'];
$abbr    = $_POST['abbr'];
$id    = $_POST['id'];

if(preg_match('/[¬`\|<>£\$%\^\+=]/', $faculty)){
//if(preg_match('/["\'¬`\|<>£\$%\^\[\]&\(\)-_\+=\{\}\?]/', $session)){
	echo 3;
}else{
			$runc = $conn->query("SELECT * FROM faculty WHERE faculty='$faculty' AND faculty_abbr='$abbr'");
			if($runc->num_rows>0){
				echo 2;
			}else{
				$sql ="UPDATE `faculty` SET `faculty`=?, `faculty_abbr`=? WHERE id ='$id'";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ss',$faculty,$abbr);
				$status = $stmt->execute();
				if(!$status){
					echo 0;
				}else{
					echo 1;
				}
		}
}

?>