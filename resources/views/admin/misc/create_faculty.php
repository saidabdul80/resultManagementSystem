<?php
include("../../php/connect.php");

$nfaculty = explode(',',$_POST['nfaculty']);
$faculty = $nfaculty[0];
$abbr    = $nfaculty[1];

if(preg_match('/[¬`\|<>£\$%\^\+=]/', $faculty)){
//if(preg_match('/["\'¬`\|<>£\$%\^\[\]&\(\)-_\+=\{\}\?]/', $session)){
	echo 3;
}else{
			$runc = $conn->query("SELECT * FROM faculty WHERE faculty='$faculty'");
			if($runc->num_rows>0){
				echo 2;
			}else{
				$sql ="INSERT INTO `faculty`(`faculty`, `faculty_abbr`) VALUES(?,?)";
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