<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$email=mysqli_real_escape_string($conn, $_POST['email']);
$password=mysqli_real_escape_string($conn, $_POST['password']);
$first_name=mysqli_real_escape_string($conn, $_POST['first_name']);
$last_name=mysqli_real_escape_string($conn, $_POST['last_name']);
$hash=password_hash($password, PASSWORD_DEFAULT);

$sql="SELECT user_email FROM allowed_users WHERE allowed_users.user_email='".$email."'";
$result=$conn->query($sql);
$num_rows=$result->num_rows;

// default return value
$returnVal=0;

// ensure entry exits
if($num_rows==1) {
	$sql="INSERT INTO users(access_id, user_email, user_password, user_first_name, user_last_name) VALUES('2', '".$email."', '".$hash."', '".$first_name."', '".$last_name."')";
	$result=$conn->query($sql);
	$returnVal=1;
}

// return value
echo $returnVal;

?>