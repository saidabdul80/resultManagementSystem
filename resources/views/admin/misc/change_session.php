<?php
include("../../php/connect.php");

	session_start();
	$user_id = $_SESSION['user_id'];
	$date = date('Y-m-d');
if(isset($_POST['type']) && @$_POST['type']==1){
	$nid = $_POST['new_session_id'];
	$oid = $_POST['old_session_id'];
	$sql0 = "UPDATE sessions SET c_set=0 WHERE id='$oid'";
	$sql1 = "UPDATE sessions SET c_set=1 WHERE id='$nid'";
	$run0 = $conn->query($sql1);
	$run1 = $conn->query($sql0);
	if ($run1 == $run0) {
		$_SESSION['current_set_session_id'] = $nid;
		$_SESSION['current_set_session']    = $_POST['name'];
		//log comment
			$des = "\<p\>Session was set from: ".$_POST['oldname']." to ".$_POST['name']."<\/p><p>by: ".$_SESSION['user_fullname']." with ID: ".$_SESSION['user_real_id']."<\/p>";
		$sqli = 1;
		$lg = $conn->query("INSERT INTO `logs`(`id`, `user_id`, `description`, `action_date`) VALUES (null,'$user_id','$des','$date')");
		echo mysqli_error($conn);
		echo 1;
	}else{
		echo 0;
	}
}else{
	//script to add new session
	$session = $_POST['session'];
	$id = $_POST['id'];
	//checking for unacceptable character in the input
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
					$sql ="UPDATE `sessions` SET `session`= ? WHERE id='$id'";
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
}

?>