<?php
use \App\User;
use \App\Role;
$user_id =$_SESSION['user_id'];

$rid = $_POST['rid'];
$uid = $_POST['uid'];
$date = date('Y-m-d h:ia');

	$nfrole = 5.','.$rid;
	User::where('id',$uid)->update(['role_id']=> $nfrole);
	echo 1;
?>