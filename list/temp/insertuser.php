<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

// $email=$_POST['email'];
// $password=$_POST['password'];
$hash=password_hash($password, PASSWORD_DEFAULT);

$sql="INSERT INTO users(user_id, user_password) VALUES ('".$email."', '".$hash."')";
$result=$conn->query($sql);

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>