<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$email=$_POST['email'];
$password=$_POST['password'];
// $hash=password_hash($password, PASSWORD_DEFAULT);

$sql="SELECT user_id, user_password FROM users WHERE users.user_id='".$email."'";
$result=$conn->query($sql);
$num_rows=$result->num_rows;

// default return value
$returnVal=0;

// ensure entry exits
if($num_rows==1) {
	$row=$result->fetch_assoc();
	if($password==$row['user_password']) {
	// if(password_verify($password, $row['user_password'])) {
		// return this value if username and password match
		$_SESSION['user_id']=$email;
		$returnVal=1;
	}
}

// return value
echo $returnVal;

?>