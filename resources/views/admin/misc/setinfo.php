<?php
session_start();
	$update = $_POST['update'];
	$id = $_POST['id'];
	$_SESSION['selected_id'] = $id;
	$_SESSION['update']      = $update;


?>