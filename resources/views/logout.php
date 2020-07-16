<?php
	session_start();
		unset($_SESSION['role'] );
		unset($_SESSION['username']);
		if(isset($_SESSION['selected_id'])) {
			unset($_SESSION['selected_id']);
			unset($_SESSION['update']);
			unset($_SESSION['current_set_session_id']);
			unset($_SESSION['current_set_session']);
			unset($_SESSION['current_set_semester_id']);
			unset($_SESSION['current_set_semester']);
		}
		echo "<script>window.location='index.php';</script>";
?>