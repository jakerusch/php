<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$location_instance_id=$_POST['location_instance_id'];
$item_instance_id=$_POST['item_instance_id'];

$sql = "INSERT INTO lists(location_instance_id, item_instance_id, qty) VALUES('".$location_instance_id."', '".$item_instance_id."', '1')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>