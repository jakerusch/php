<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$location_title = mysqli_real_escape_string($conn, $_POST['location_title']);

$sql = "INSERT INTO master_locations(user_id, location_name) VALUES('".$sid."', '".$location_title."')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>