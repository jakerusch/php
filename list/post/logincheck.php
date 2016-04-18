<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$email=mysqli_real_escape_string($conn, $_POST['email']);
$password=mysqli_real_escape_string($conn, $_POST['password']);
$hash=password_hash($password, PASSWORD_DEFAULT);

$sql="SELECT user_email, user_password, user_id FROM users WHERE users.user_email='".$email."'";
$result=$conn->query($sql);
$num_rows=$result->num_rows;

// default return value
$returnVal=0;

// ensure entry exits
if($num_rows==1) {
	$row=$result->fetch_assoc();
	// if($password==$row['user_password']) {
	if(password_verify($password, $row['user_password'])) {
		// return this value if username and password match
		$_SESSION['user_id']=$row['user_id'];
		$returnVal=1;
	}
}

// return value
echo $returnVal;

?>