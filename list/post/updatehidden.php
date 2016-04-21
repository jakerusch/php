<?php
require_once($_SERVER['DOCUMENT_ROOT']."/list/include/classyJake.php");
$obj = new classyJake();
$conn = $obj->getConn();

$location_instance_id=$_POST['location_instance_id'];
$item_instance_id=$_POST['item_instance_id'];
$status=$_POST['status'];

$sql = "UPDATE lists SET checked_status='".$status."' WHERE location_instance_id='".$location_instance_id."' AND item_instance_id='".$item_instance_id."'";
if ($conn->query($sql) === TRUE) {
    echo 1;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>