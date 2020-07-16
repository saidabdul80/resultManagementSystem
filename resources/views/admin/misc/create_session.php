<?php
include("../../php/connect.php");

$session = $_POST['nsession'];
if(preg_match('/["\'¬`\|<>£\$%\^\[\]&\(\)-\+=\{\}\?]/', $session)){
//if(preg_match('/["\'¬`\|<>£\$%\^\[\]&\(\)-_\+=\{\}\?]/', $session)){
	echo 3;
}else{
	if (preg_match('/[a-z]/', $session)) {
		echo 3;
	}else{
		if (preg_match('/[A-Z]/', $session)) {
		echo 3;
		}else{
		 	$session = str_replace('_', '/', $session);
		 	//echo $session;
			$runc = $conn->query("SELECT * FROM sessions WHERE session='$session'");
			if($runc->num_rows>0){
				echo 2;
			}else{
				$sql ="INSERT INTO `sessions`(`session`) VALUES(?)";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('s',$session);
				$status = $stmt->execute();
				if(!$status){
					echo 0;
				}else{
					echo 1;
				}
		}
	}
	}
}

?>