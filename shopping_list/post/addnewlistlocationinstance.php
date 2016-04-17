<?php
require_once($_SERVER['DOCUMENT_ROOT']."/shopping_list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();
$sid=$_SESSION['user_id'];

$location_id=$_POST["location_id"];

// creates new list location instance
$sql = "INSERT INTO location_instances(location_id, user_id) VALUES('".$location_id."', '".$sid."')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>