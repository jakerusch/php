<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$user_email=mysqli_real_escape_string($conn, $_POST['user_email']);

$sql = "INSERT INTO allowed_users(user_email) VALUES('".$user_email."')";
if ($conn->query($sql) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>